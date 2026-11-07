<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_only_published_recipes(): void
    {
        $published = Recipe::factory()->create();
        $draft = Recipe::factory()->draft()->create();

        $response = $this->getJson('/api/recipes')->assertOk();

        $slugs = collect($response->json('recipes'))->pluck('slug');
        $this->assertTrue($slugs->contains($published->slug));
        $this->assertFalse($slugs->contains($draft->slug));
    }

    public function test_show_returns_recipe_details(): void
    {
        $recipe = Recipe::factory()->create();

        $this->getJson("/api/recipes/{$recipe->slug}")
            ->assertOk()
            ->assertJsonPath('recipe.slug', $recipe->slug)
            ->assertJsonPath('recipe.ingredients.0.name', 'Farine');
    }

    public function test_draft_recipe_is_not_publicly_visible(): void
    {
        $draft = Recipe::factory()->draft()->create();

        $this->getJson("/api/recipes/{$draft->slug}")->assertNotFound();
    }

    public function test_only_approved_comments_are_publicly_visible(): void
    {
        $recipe = Recipe::factory()->create();
        $author = User::factory()->create(['status' => 'active']);

        Comment::create(['user_id' => $author->id, 'recipe_id' => $recipe->id, 'body' => 'Visible', 'is_approved' => true]);
        Comment::create(['user_id' => $author->id, 'recipe_id' => $recipe->id, 'body' => 'En attente', 'is_approved' => false]);

        $listed = collect($this->getJson("/api/recipes/{$recipe->slug}/comments")->assertOk()->json('comments'))->pluck('body');
        $this->assertEquals(['Visible'], $listed->all());

        $inDetail = collect($this->getJson("/api/recipes/{$recipe->slug}")->json('recipe.comments'))->pluck('body');
        $this->assertEquals(['Visible'], $inDetail->all());
    }

    public function test_like_toggles_on_and_off(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'sanctum')->postJson("/api/recipes/{$recipe->slug}/like")
            ->assertOk()->assertJson(['liked' => true, 'likes_count' => 1]);

        $this->actingAs($user, 'sanctum')->postJson("/api/recipes/{$recipe->slug}/like")
            ->assertOk()->assertJson(['liked' => false, 'likes_count' => 0]);
    }

    public function test_liking_requires_authentication(): void
    {
        $recipe = Recipe::factory()->create();

        $this->postJson("/api/recipes/{$recipe->slug}/like")->assertUnauthorized();
    }

    public function test_clean_comment_is_published(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/recipes/{$recipe->slug}/comments", ['body' => 'Délicieux, merci pour la recette !'])
            ->assertCreated();

        $this->assertDatabaseHas('comments', ['recipe_id' => $recipe->id, 'is_approved' => true]);
    }

    public function test_offensive_comment_is_rejected(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/recipes/{$recipe->slug}/comments", ['body' => 'Quelle recette de crétin'])
            ->assertUnprocessable();

        $this->assertDatabaseCount('comments', 0);
    }
}
