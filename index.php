<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/vanillaSelectBox.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-dark bg-dark justify-content-start">
            <button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Настройки
            </button>
        </nav>
        <table class="table table-dark">
            <thead>
            <tr>
                <th>ID валюты</th>
                <th>Номерной код валюты</th>
                <th>Символьный код валюты</th>
                <th>Номинал</th>
                <th>Название</th>
                <th>Стоимость</th>
            </tr>
            </thead>
            <tbody id="tableBody">
                <div class="close loader-wrapper" id="loader">
                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                </div>
            </tbody>
        </table>
    </div>

    <!-- Модальное окно -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Настройки отображения</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>

                <div class="modal-body">
                    <div>
                        <label>Выберите валюты, которые вы хотите отображать</label>
                        <div id='watch-config' class="watch-config">

                        </div>
                    </div>
                    <hr/>

                    <div>
                        <label>Выберите валюты, по которым вы хотите обновлять данные</label>
                        <div id='refresh-config' class="watch-config">

                        </div>
                    </div>

                    <hr/>

                    <div>
                        <label>Определите интервал обновления данных (в минутах)</label>
                        <input
                                class="form-control"
                                id="refresh-interval"
                                type="number"
                        >
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" id="submitButton" class="btn btn-primary" onclick="saveConf()">Сохранить изменения</button>
                </div>

            </div>
        </div>
    </div>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>