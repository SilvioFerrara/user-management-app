<?php

/*
 * =========================
 *  MODEL: User
 * =========================
 * Classe che rappresenta l'entità "User".
 * È un semplice modello dati (Data Model).
 * Contiene proprietà private e metodi getter.
 */

class User {

    // Proprietà private: accessibili solo all'interno della classe
    // Type hint "string" garantisce che il valore sia una stringa
    private string $name;
    private string $email;
    private string $birthdate;

    /*
     * Costruttore della classe.
     * Viene chiamato quando si crea un nuovo oggetto User.
     * Inizializza le proprietà con i valori passati come parametri.
     */
    public function __construct(string $name, string $email, string $birthdate) {
        $this->name = $name;           // Assegna il nome
        $this->email = $email;         // Assegna l'email
        $this->birthdate = $birthdate; // Assegna la data di nascita
    }

    /*
     * Getter del nome.
     * Restituisce il valore della proprietà $name.
     */
    public function getName(): string {
        return $this->name;
    }

    /*
     * Getter dell'email.
     * Restituisce il valore della proprietà $email.
     */
    public function getEmail(): string {
        return $this->email;
    }

    /*
     * Getter della data di nascita.
     * Restituisce il valore della proprietà $birthdate.
     */
    public function getBirthdate(): string {
        return $this->birthdate;
    }
}
