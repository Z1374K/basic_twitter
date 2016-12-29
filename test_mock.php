<?php
//rozpocznij sesję (do której docelowo przypiszemy zalogowanego użytkownika). Domyślnie (bez unseta) trwa 24 minuty, zastanów się, czy tego nie zmodyfikować
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form method="post" action="">
            <input name="email" type="email" value="" placeholder="e-mail"/><br/>
            <input name="password" type="password" value="" placeholder="password"/><br/>
            <button type="submit" name="submit" value="entry_submit">Done</button>

        </form>
        <?php
        //sprawdź czy zostały wprowadzone dane przez formularz POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'src/user.php';
            //połącz z bazą danych
            $connection = new PrototypeConnection('warsztaty');
            User::$conn = $connection->getConn();
            //przyjmij wartości email i password do zmiennych (przyda się później do sanitizing queries chyba)
            $email = $_POST['email'];
            $pass = $_POST['password'];
            //sprawdź czy podany w formularzu logowania email znajduje się w bazie (to logowanie, nie rejestracja)
            $result = User::$conn->query("SELECT email, hashed_password FROM Users WHERE email = '$email'");
            //jeśli znalazło...
            if ($result->num_rows == 1) {
                //zbierz wartości do zmiennej $row, która jest tablicą zawierającą dane o zalogowanym użytkowniku
                $row = $result->fetch_assoc();
                //sprawdź czy podane hasło zgadza się z hasłem w bazie danych
                if (password_verify($pass, $row['hashed_password'])) {
                    $_SESSION['login'] = $row['email'];
                    echo "zalogowano: " . $_SESSION['login'];
                } else {
                    echo "invalid password";
                }
            } else {
                //tutaj potem warto dodać link do strony z rejestracją
                echo "Nie ma takiego użytkownika";
            }
            if (!isset($_SESSION['login'])) {
                echo '<li><a href=Users.php">Login</a></li>';
            } else {
                echo '<li><a href="Logowanie.php">Logout</a></li>';
                echo "<br>";
                echo 'Logowanie przebiegło pomyślnie';
            }
        }
        ?>
    </body>
</html>