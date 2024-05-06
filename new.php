<?php
session_start();


if (!isset($_SESSION['historico'])) {
    $_SESSION['historico'] = [];
}

if (!isset($_SESSION['memoria'])) {
    $_SESSION['memoria'] = null;
}

$resultado = "";
$num1 = "";
$num2 = "";
$operacao = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["clearHistory"])) {
        $_SESSION['historico'] = [];
    }

    if (isset($_POST["clearMemory"])) {
        $_SESSION['memoria'] = null;
    }

    if (isset($_POST["memory"])) {
        if ($_SESSION['memoria'] === null || !is_array($_SESSION['memoria'])) {

            $_SESSION['memoria'] = [
                'num1' => $_POST['num1'] ?? "",
                'num2' => $_POST['num2'] ?? "",
                'operacao' => $_POST['operacao'] ?? ""
            ];
        } else {

            $memoria = $_SESSION['memoria'];
            $num1 = $memoria['num1'];
            $num2 = $memoria['num2'];
            $operacao = $memoria['operacao'];
        }
    } else {
        $num1 = $_POST["num1"] ?? "";
        $num2 = $_POST["num2"] ?? "";
        $operacao = $_POST["operacao"] ?? "";
    }

    if ($operacao) {
        switch ($operacao) {
            case "soma":
                $resultado = $num1 + $num2;
                break;
            case "subtracao":
                $resultado = $num1 - $num2;
                break;
            case "multiplicacao":
                $resultado = $num1 * $num2;
                break;
            case "divisao":
                if ($num2 == 0) {
                    $resultado = "Erro: Divisão por zero";
                } else {
                    $resultado = $num1 / $num2;
                }
                break;
            case "potencia":
                $resultado = pow($num1, $num2);
                break;
            case "fatoracao":
                if ($num1 >= 0) {
                    $resultado = factorial($num1);
                } else {
                    $resultado = "Erro: Fatoração de número negativo";
                }
                break;
            default:
                $resultado = "Operação inválida";
        }

        $_SESSION['historico'][] = "$num1 $operacao $num2 = $resultado";
    }
}

function factorial($n) {
    if ($n == 0) {
        return 1;
    }
    return $n * factorial($n - 1);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Calculadora Gamer</title>
</head>
<body>
    <div class="calculadora">
        <h1>Calculadora Gamer</h1>
        <form method="post">
            <div>
                <label para="num1">Número 1:</label>
                <input type="number" name="num1" id="num1" value="<?= $num1 ?>" step="any" required>
            </div>
            <div>
                <label para="num2">Número 2:</label>
                <input type="number" name="num2" id="num2" value="<?= $num2 ?>" step="any">
            </div>
            <div>
                <label para="operacao">Operação:</label>
                <select name="operacao" id="operacao" required>
                    <option value="soma">+</option>
                 <option value="subtracao">-</option>
                    <option value="multiplicacao">*</option>
                    <option value="divisao">/</option>
                    <option value="potencia">^</option>
                    <option value="fatoracao">!</option>
                </select>
            </div>
            <button type="submit">Calcular</button>
            <button type="submit" name="memory">M</button>
            <button type="submit" name="clearMemory">Limpar Memória</button>
        </form>
        <div class="resultado">
            <h2>Resultado:</h2>
            <p><?= $resultado ?></p>
        </div>
        <div class="historico">
            <h2>Histórico:</h2>
            <ul>
                <?php
                foreach ($_SESSION['historico'] as $item) {
                    echo "<li>$item</li>";
                }
                ?>
            </ul>
            <form method="post">
                <button type="submit" name="clearHistory">Limpar Histórico</button>
           </form>
     </div>
 </div>
</body>
</html>
