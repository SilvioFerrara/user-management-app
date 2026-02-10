/*
Hook di React:
useState → Per salvare dati. Serve per gestire lo stato del componente
useEffect → Per eseguire codice automaticamente. serve per eseguire operazioni collaterali (side effects), come chiamate API
*/
import { useEffect, useState } from "react";

//funzione JavaScript chiamata App. In React, una funzione di questo tipo è un componente
function App() {
  /*
  State hook.
  users → contiene la lista degli utenti
  setUsers → funzione per aggiornare users
  useState([]) → valore iniziale: array vuoto
  All’inizio l’app non ha utenti, poi verranno caricati dal backend.
  */
  const [users, setUsers] = useState([]);
  
  //contenuto del form
  //messaggi di errore
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [birthdate, setBirthdate] = useState("");
  const [error, setError] = useState("");

  
  useEffect(() => {//Esegui questo codice una sola volta, quando il componente viene montato
    loadUsers();
  }, []); //Le parentesi [] vogliono dire: esegui una sola volta

  //Serve per caricare la lista utenti dal backend.
  function loadUsers() {
    fetch("http://localhost:8000/index.php?action=list")//Chiama il backend PHP (GET)
      .then((res) => res.json()) //Trasforma la risposta in JSON
      .then((data) => {
        setUsers(data.data);//Aggiorna users, React aggiorna automaticamente la pagina
      });
  }

  //handleSubmit (submit del form)
  function handleSubmit(e) {
    e.preventDefault();//Blocca il comportamento classico del form (refresh pagina).

    //chiamata POST
    fetch("http://localhost:8000/index.php?action=add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json", //Diciamo a PHP:“Ti mando JSON”
      },
      
      //Convertiamo i dati del form in JSON.
      body: JSON.stringify({
        name,
        email,
        birthdate,
      }),
    })
      .then((res) => res.json())
      //Se PHP risponde errore → mostriamo messaggio.
      .then((data) => {
        if (!data.success) {
          setError(data.error);
        } else {
          setError("");
          
          //reset form, Puliamo il form dopo il salvataggio.
          setName("");
          setEmail("");
          setBirthdate("");
          //ricaricare la lista , Mostriamo subito il nuovo utente.
          loadUsers();
        }
      });
  }

  //Questo è HTML scritto in JavaScript (JSX).
  return (
    <div>
      <h1>User Management</h1>

      <h2>Nuovo utente</h2>

      {error && <p style={{ color: "red" }}>{error}</p>}

      <form onSubmit={handleSubmit}>
        <input
          placeholder="Nome"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        <br />

        <input
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <br />

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
        {users.map((user, index) => (
          <li key={index}>
            {user.name} – {user.email} – {user.birthdate}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;
