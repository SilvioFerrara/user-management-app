/*
 =========================
  IMPORT LIBRERIE REACT
 =========================
*/

// StrictMode è uno strumento di sviluppo.
// Aiuta a individuare potenziali problemi nell'app
// (es. uso scorretto di lifecycle, effetti doppi in dev, ecc.)
import { StrictMode } from 'react'

// createRoot serve per inizializzare l'app React
// (nuova API introdotta in React 18)
import { createRoot } from 'react-dom/client'


/*
 =========================
  IMPORT FILE LOCALI
 =========================
*/

// Import del file CSS globale
// Viene applicato a tutta l'applicazione
import './index.css'

// Import del componente principale dell'app
import App from './App.jsx'


/*
 =========================
  BOOTSTRAP DELL'APPLICAZIONE
 =========================
*/

// document.getElementById('root')
// Recupera il div root presente in index.html
// <div id="root"></div>

// createRoot(...) crea il punto di aggancio dell'app React
// .render(...) renderizza il componente dentro il DOM
createRoot(document.getElementById('root')).render(

  // StrictMode avvolge l'app
  // Attivo solo in sviluppo (non influisce in produzione)
  <StrictMode>

    {/* Componente principale */}
    <App />

  </StrictMode>,
)
