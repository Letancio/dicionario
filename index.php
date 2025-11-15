<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dicinteligente</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Fonte estilo New York Times -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Merriweather', serif;
            background-color: #f8f8f5;
            color: #111;
        }

        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h1 {
            font-size: 3.2rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #444;
        }

        /* Estilo estilo editorial */
        .nyt-input {
            width: 300px;
            padding: 10px 14px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 2px;
            outline: none;
            transition: all .2s ease;
        }

        .nyt-input:focus {
            border-color: #000;
        }

        .nyt-btn {
            margin-top: 15px;
            background-color: #000;
            color: #fff;
            padding: 10px 28px;
            border-radius: 2px;
            border: none;
            font-size: 1rem;
            transition: 0.2s;
            display: inline-block;
            text-decoration: none;
        }

        .nyt-btn:hover {
            background-color: #333;
            color: #fff;
        }

        form {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="hero">
        <h1>Dicinteligente</h1>
        <p class="subtitle">Seu dicionário online</p>
        <div id="resultado"></div>
        <form method="GET" action="" id="formBusca">
            <input type="text" name="termo" id="termo" class="nyt-input" placeholder="Informe sua palavra">

            <br>

            <button class="nyt-btn" type="submit">
                Pesquisar Palavra
            </button>
        </form>

    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        // Quando enviar o formulário
        $("#formBusca").on("submit", function (e) {
            e.preventDefault();

            let termo = $("#termo").val().trim();
            if (termo === "") return;

            // Oculta formulário e mostra preload
            $("#formBusca").hide();
            $("#resultado").html(`
            <div class="d-flex justify-content-center mt-4">
                <div class="spinner-border text-dark" role="status"></div>
            </div>
            <p class="mt-3">Consultando a palavra…</p>
        `).show();

            // Chamada AJAX
            $.ajax({
                url: "src/consultaController.php",
                type: "POST",
                data: { termo: termo },

                success: function (response) {

                    // Substitui preload pela resposta da API
                    $("#resultado").html(`
                    <div class="mt-4 p-3 border rounded bg-white shadow-sm text-left">
                        ${response}
                    </div>

                    <button id="buscarNovamente" class="nyt-btn mt-3">
                        Buscar novamente
                    </button>
                `);
                },

                error: function () {
                    $("#resultado").html(`
                    <p class="text-danger mt-3">
                        Erro ao consultar a API.
                    </p>
                `);
                }
            });
        });

        // Evento do botão "buscar novamente"
        $(document).on("click", "#buscarNovamente", function () {
            $("#resultado").hide().html("");
            $("#formBusca")[0].reset();
            $("#formBusca").show();
        });

    });
</script>

</html>