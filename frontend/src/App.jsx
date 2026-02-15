/*
 =========================
  IMPORT HOOK REACT
 =========================

 useState  → Permette di creare e gestire lo stato del componente
 useEffect → Permette di eseguire effetti collaterali (es. chiamate API)
*/
import { useEffect, useState } from "react";


/*
 =========================
  COMPONENTE PRINCIPALE
 =========================

 In React, una funzione che restituisce JSX è un componente.
 App è il componente principale dell'applicazione.
*/
function App() {

  /*
   =========================
    STATE MANAGEMENT
   =========================
  */

  // Lista utenti caricati dal backend
  // users → stato
  // setUsers → funzione per aggiornare lo stato
  // [] → valore iniziale: array vuoto
  const [users, setUsers] = useState([]);

  // Stati per il form
  const [name, setName] = useState("");         // Campo nome
  const [email, setEmail] = useState("");       // Campo email
  const [birthdate, setBirthdate] = useState(""); // Campo data di nascita

  // Stato per eventuali messaggi di errore
  const [error, setError] = useState("");


  /*
   =========================
    USE EFFECT
   =========================

   Viene eseguito una sola volta al montaggio del componente
   (grazie all'array di dipendenze vuoto [])
  */
  useEffect(() => {
    loadUsers(); // Carica utenti all'avvio
  }, []);


  /*
   =========================
    FUNZIONE: CARICA UTENTI
   =========================

   Effettua una richiesta GET al backend PHP
   per ottenere la lista utenti.
  */
  function loadUsers() {

    fetch("http://localhost:8000/index.php?action=list")
      .then((res) => res.json()) // Converte la risposta in JSON
      .then((data) => {

        // Aggiorna lo stato users
        // React aggiorna automaticamente il DOM
        setUsers(data.data);
      });
  }


  /*
   =========================
    FUNZIONE: HANDLE SUBMIT
   =========================

   Gestisce l'invio del form (POST verso il backend)
  */
  function handleSubmit(e) {

    // Previene il refresh automatico della pagina
    e.preventDefault();

    // Chiamata POST al backend
    fetch("http://localhost:8000/index.php?action=add", {
      method: "POST",

      // Header per indicare che stiamo inviando JSON
      headers: {
        "Content-Type": "application/json",
      },

      // Converte i dati del form in JSON
      body: JSON.stringify({
        name,
        email,
        birthdate,
      }),
    })
      .then((res) => res.json())
      .then((data) => {

        // Se il backend restituisce errore
        if (!data.success) {

          // Mostra messaggio di errore
          setError(data.error);

        } else {

          // Reset messaggio errore
          setError("");

          // Pulizia del form
          setName("");
          setEmail("");
          setBirthdate("");

          // Ricarica lista utenti per mostrare il nuovo inserimento
          loadUsers();
        }
      });
  }


  /*
   =========================
    RENDER (JSX)
   =========================

   JSX = HTML scritto dentro JavaScript
   React trasforma questo codice in DOM reale
  */
  return (
    <div>
      <h1>User Management</h1>

      <h2>Nuovo utente</h2>

      {/* Se esiste un errore, mostralo */}
      {error && <p style={{ color: "red" }}>{error}</p>}

      {/* Form con gestione submit */}
      <form onSubmit={handleSubmit}>

        {/* Input Nome */}
        <input
          placeholder="Nome"
          value={name} // Collegato allo stato
          onChange={(e) => setName(e.target.value)} // Aggiorna stato
        />
        <br />

        {/* Input Email */}
        <input
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <br />

        {/* Input Data */}
        <input
          type="date"
          value={birthdate}
          onChange={(e) => setBirthdate(e.target.value)}
        />
        <br />

        <button type="submit">Salva</button>
      </form>

      <h2>Lista utenti</h2>

      {/* Render dinamico lista utenti */}
      <ul>
        {users.map((user, index) => (

          // key serve a React per identificare elementi della lista
          <li key={index}>
            {user.name} – {user.email} – {user.birthdate}
          </li>
        ))}
      </ul>
    </div>
  );
}

// Esporta il componente per poterlo usare altrove
export default App;
