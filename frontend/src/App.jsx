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

  // Stato che identifica se siamo in modalità modifica
  // null → modalità inserimento
  // id   → modalità update
  const [editingId, setEditingId] = useState(null);



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
   =====================================================
    FUNZIONE: HANDLE SUBMIT
   =====================================================

   Gestisce sia:
   - Creazione nuovo utente (POST)
   - Modifica utente esistente (PUT)

   La logica è dinamica in base a editingId.
  */
function handleSubmit(e) {
  
  // Previene il comportamento di default del form (refresh pagina)
  e.preventDefault();

  // Se editingId esiste → update
    // Altrimenti → add
  const action = editingId ? "update" : "add";        // check se update o add
  const method = editingId ? "PUT" : "POST";          // PUT per update, POST per add

  fetch(`http://localhost:8000/index.php?action=${action}`, {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: editingId,       // serve solo per update
      name,
      email,
      birthdate,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        // Mostra errore proveniente dal backend
        setError(data.error);
      } else {
        // Reset stato errori
        setError("");

        // Pulizia form
        setName("");
        setEmail("");
        setBirthdate("");

        // Uscita modalità modifica
        setEditingId(null);  // 🔥 reset modalità modifica

        // Aggiorna lista utenti
        loadUsers();         // ricarica lista utenti
      }
    });
}
  

  /*
   =====================================================
    FUNZIONE: DELETE USER
   =====================================================

   Effettua una richiesta DELETE al backend
   passando l'id come query parameter.
  */
  function deleteUser(id) {

  fetch(`http://localhost:8000/index.php?action=delete&id=${id}`, {
    method: "DELETE",
  })
    .then((res) => res.json())
    .then(() => {
      // Ricarica lista utenti dopo eliminazione
      loadUsers();
    });
}

/*
   =====================================================
    FUNZIONE: START EDIT
   =====================================================

   Attiva la modalità modifica:
   - Imposta editingId
   - Popola il form con i dati dell’utente selezionato
  */
function startEdit(user) {
  setEditingId(user.id);     // imposta l'id dell'utente in modifica
  setName(user.name);        // popola il form
  setEmail(user.email);
  setBirthdate(user.birthdate);
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

      <ul>
        {users.map((user) => (
          <li key={user.id}>
            {user.name} – {user.email} – {user.birthdate}

            <button onClick={() => startEdit(user)}>
              Modifica
            </button>

            <button onClick={() => deleteUser(user.id)}>
              Elimina
            </button>
          </li>
        ))}
      </ul>

    </div>
  );
}

// Esporta il componente per poterlo usare altrove
export default App;
