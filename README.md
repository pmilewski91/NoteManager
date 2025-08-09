# NoteManager

NoteManager to przykładowa aplikacja webowa napisana w PHP z wykorzystaniem OOP, wzorców projektowych oraz Dockera. Projekt pokazuje umiejętność pracy z PHP, Dockerem, Composerem, bazą danych oraz prostą autoryzacją użytkowników.

## Funkcje

- Rejestracja i logowanie użytkowników
- Dodawanie, wyświetlanie i usuwanie notatek
- Prosty interfejs oparty o Bootstrap
- Bezpieczne hashowanie haseł
- Obsługa AJAX dla logowania i usuwania notatek
- Praca z bazą danych MySQL/MariaDB

## Wymagania

- Docker & Docker Compose
- PHP 8.1+
- Composer

## Instalacja i uruchomienie

1. **Sklonuj repozytorium:**
   ```bash
   git clone https://github.com/pmilewski91/NoteManager.git
   cd NoteManager
   ```

2. **Zbuduj i uruchom kontenery Docker:**
   ```bash
   docker-compose up -d
   ```

3. **Zainstaluj zależności PHP (Composer):**
   Jeśli korzystasz z Dockera, możesz wykonać:
   ```bash
   docker-compose exec app composer install
   ```
   Lub lokalnie:
   ```bash
   composer install
   ```

4. **Baza danych i tabele:**
   Po uruchomieniu kontenerów baza powinna być dostępna. Nie musisz ręcznie tworzyć tabel – aplikacja automatycznie utworzy wymagane tabele oraz przykładowego użytkownika przy pierwszym uruchomieniu, dzięki mechanizmowi w pliku `Database.php`.

5. **Otwórz aplikację w przeglądarce:**
   ```
   http://localhost:8080
   ```
   (lub inny port, jeśli zmieniłeś w `docker-compose.yml`)

## Dane dostępowe do bazy (przykład)

W pliku `.env` lub w konfiguracji Dockera:
```
DB_HOST=db
DB_NAME=notemanager
DB_USER=root
DB_PASSWORD=haslo
```

## Struktura projektu

- `src/` – kod źródłowy aplikacji (modele, repozytoria, serwisy)
- `/` katalog główny – pliki wejściowe (index.php, login.php, register.php, itd.)
- `vendor/` – zależności Composer (ignorowane przez git)
- `.gitignore` – ignorowane pliki i katalogi

## Technologie

- PHP (OOP)
- MySQL/MariaDB
- Docker & Docker Compose
- Composer
- Bootstrap

## Autor

Projekt demonstracyjny – pokazuje umiejętność pracy z PHP, OOP, Dockerem, bazą danych oraz przekazywaniem danych