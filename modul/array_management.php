<!DOCTYPE html>
<html>
<head>
    <title>Kalkulator PHP</title>
</head>
<body>
    <h2>Kalkulator Sederhana</h2>
    <form method="post">
        <input type="number" name="num1" placeholder="Angka 1" required>
        <select name="operator">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">×</option>
            <option value="/">/</option>
        </select>
        <input type="number" name="num2" placeholder="Angka 2" required>
        <button type="submit" name="calculate">Hitung</button>
    </form>

    <?php
    if (isset($_POST['calculate'])) {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $operator = $_POST['operator'];
        
        function calculate($num1, $num2, $operator) {
            switch ($operator) {
                case '+':
                    return $num1 + $num2;
                case '-':
                    return $num1 - $num2;
                case '*':
                    return $num1 * $num2;
                case '/':
                    if ($num2 != 0) {
                        return $num1 / $num2;
                    } else {
                        return "Error: Pembagian dengan nol!";
                    }
                default:
                    return "Error: Operator tidak valid!";
            }
        }
        
        $result = calculate($num1, $num2, $operator);
        echo "<h3>Hasil: $num1 $operator $num2 = $result</h3>";
    }
    ?>
</body>
</html>