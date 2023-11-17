<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
    <?php
        $apiKey = '0d5a27ac0764e80dd43e85bd';
        $apiUrl = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/EUR";

        // Récupérer les données JSON depuis l'API
        $response = file_get_contents($apiUrl);

        if ($response) {
            $data = json_decode($response, true);

            if ($data['result'] == 'success') {
                $currencies = array_keys($data['conversion_rates']);
            }
        }
    ?>
    <div class="wrapper">

        <h1>Currency.<br><span>Converter</span></h1>

        <form method="post" action="">
            <!-- ------- INPUT ------- -->
            <input type="number" placeholder="0" name="amount" id="amount" value="<?php echo isset($_POST['amount']) ? number_format($_POST['amount'], 2, '.', '') : ''; ?>">

            <select name="base_currency" id="from_currency">
                <?php
                // Affichage des devises disponibles
                if (isset($currencies)) {
                    foreach ($currencies as $currency) {
                        echo "<option value=\"{$currency}\"";
                        if (isset($_POST['base_currency']) && $_POST['base_currency'] === $currency) {
                            echo " selected";
                        }
                        echo ">{$currency}</option>";
                    }
                }
                ?>
                <span class="material-symbols-outlined">expand_more</span>
            </select>

            <!-- ------- OUTPUT ------- -->
            <input type="text" placeholder="Convert to" name="result" id="result" value="<?php
                if (isset($_POST['amount']) && isset($_POST['base_currency']) && isset($_POST['target_currency'])) {
                    $amount = $_POST['amount'];
                    $baseCurrency = $_POST['base_currency'];
                    $targetCurrency = $_POST['target_currency'];
                    $rates = $data['conversion_rates'];
                    $result = ($amount * $rates[$targetCurrency]) / $rates[$baseCurrency];
                    echo number_format($result, 2, '.', '');
                } else {
                    echo '';
                }
            ?>" readonly>

            <select name="target_currency" id="to_currency">
                <span class="material-symbols-outlined">expand_more</span>
                <?php
                // Affichage des devises disponibles
                if (isset($currencies)) {
                    foreach ($currencies as $currency) {
                        echo "<option value=\"{$currency}\"";
                        if (isset($_POST['target_currency']) && $_POST['target_currency'] === $currency) {
                            echo " selected";
                        }
                        echo ">{$currency}</option>";
                    }
                }
                ?>
            </select>


            <!-- ------- SUBMIT ------- -->
            <button class="convert" type="submit">Convert</button>
            <button class="revert">
                <p>Revert</p>
            </button>
        </form>
    </div>
</body>
</html>