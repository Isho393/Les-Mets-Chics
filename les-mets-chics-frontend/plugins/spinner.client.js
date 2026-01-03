export default defineNuxtPlugin(() => {
  if (process.client) {
    // Injecter le spinner dans le DOM dès que possible
    const spinnerHTML = `
      <div id="initial-spinner" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
      ">
        <div style="
          width: 20em;
          height: 20em;
          font-size: 10px;
          position: relative;
          display: flex;
          align-items: center;
          justify-content: center;
        ">
          <div style="
            position: absolute;
            border-radius: 50%;
            border-style: solid;
            animation: spin 3s linear infinite;
            width: 100%;
            height: 100%;
            color: #bfa094;
            border-color: currentColor transparent transparent currentColor;
            border-width: 0.2em 0.2em 0em 0em;
            --deg: -45deg;
            animation-direction: normal;
          ">
            <div style="
              position: absolute;
              width: 50%;
              height: 0.1em;
              top: 50%;
              left: 50%;
              background-color: transparent;
              transform: rotate(-45deg);
              transform-origin: left;
            ">
              <div style="
                position: absolute;
                top: -0.5em;
                right: -0.5em;
                content: '';
                width: 1em;
                height: 1em;
                background-color: currentColor;
                border-radius: 50%;
                box-shadow: 0 0 2em, 0 0 4em, 0 0 6em, 0 0 8em, 0 0 10em, 0 0 0 0.5em rgba(191, 160, 148, 0.1);
              "></div>
            </div>
          </div>
          <div style="
            position: absolute;
            border-radius: 50%;
            border-style: solid;
            animation: spin 3s linear infinite reverse;
            width: 70%;
            height: 70%;
            color: #22c55e;
            border-color: currentColor currentColor transparent transparent;
            border-width: 0.2em 0em 0em 0.2em;
            --deg: -135deg;
          ">
            <div style="
              position: absolute;
              width: 50%;
              height: 0.1em;
              top: 50%;
              left: 50%;
              background-color: transparent;
              transform: rotate(-135deg);
              transform-origin: left;
            ">
              <div style="
                position: absolute;
                top: -0.5em;
                right: -0.5em;
                content: '';
                width: 1em;
                height: 1em;
                background-color: currentColor;
                border-radius: 50%;
                box-shadow: 0 0 2em, 0 0 4em, 0 0 6em, 0 0 8em, 0 0 10em, 0 0 0 0.5em rgba(34, 197, 94, 0.1);
              "></div>
            </div>
          </div>
        </div>
      </div>
      <style>
        @keyframes spin {
          to { transform: rotate(1turn); }
        }
      </style>
    `
    
    document.body.insertAdjacentHTML('afterbegin', spinnerHTML)
    
    // Fonction globale pour masquer le spinner
    window.hideInitialSpinner = () => {
      const spinner = document.getElementById('initial-spinner')
      if (spinner) {
        spinner.style.opacity = '0'
        spinner.style.transition = 'opacity 0.3s ease-out'
        setTimeout(() => spinner.remove(), 600)
      }
    }
  }
})