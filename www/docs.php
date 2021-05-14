<?php
include "partials/header.php";
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered text-center">
                    <thead>
                    <h2 class="text-center">Rozdelenie úloh</h2>
                    <br>
                    <tr>
                        <th> Task </th>
                        <th> Jakub Rosina </th>
                        <th> Samuel Adler </th>
                        <th> Dávid Zabák </th>
                        <th> Martin Pač </th>
                        <th> Juraj Rak </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>Prihlásenie (študent, učiteľ)</td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Registrácia (učiteľ)</td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Pridanie testu s otázkami</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Ukončenie testu</td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                    </tr>

                    <tr>
                        <td>Administrácia na strane učiteľa</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Prostredie študenta</td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                    </tr>

                    <tr>
                        <td>Info pre učiteľa o zbiehaní testov</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Vyhodnotenie testov</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>PDF export</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>CSV export</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Docker balíček</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Grafický layout</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                    </tr>

                    <tr>
                        <td>Návrh DB</td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                        <td><i class="bi bi-check-circle-fill"></i></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <h2 class="text-center">Dokumentácia</h2>
            <div>
                <ul>
                    <li class="nav-item"><b>Inštalácia composeru</b></li>
                    <ul>
                        <li class="nav-item">príkaz: <b>composer install</b></li>
                        <li class="nav-item">inštaláciu je potrebné spustiť v priečinku www</li>
                    </ul>

                    <li class="nav-item"><b>Spustenie docker balíka</b></li>
                    <ul>
                        <li class="nav-item">ak docker klienta nemáte -> <a target="_blank" href="https://www.docker.com/products/docker-desktop">stiahnite tu</a></li>
                        <li class="nav-item">príkaz: <b>docker-compose up</b></li>
                        <li class="nav-item">inštaláciu je potrebné spustiť v domovskom adresári projektu</li>
                        <li class="nav-item"><b>port 8000</b> - phpmyadmin</li>
                        <li class="nav-item"><b>port 8001</b> - webová aplikácia</li>
                    </ul>

                    <li class="nav-item"><b>Export databázy</b></li>
                    <ul>
                        <li class="nav-item">súbor .sql sa nachádza v priečinku <b>dump</b></li>
                    </ul>

                    <li class="nav-item"><b>Prístupové údaje k phpmyadmin</b></li>
                    <ul>
                        <li class="nav-item">meno: root</li>
                        <li class="nav-item">heslo: admin</li>
                    </ul>

                    <li class="nav-item"><b>Prístupové údaje do admin prostredia v aplikácii</b></li>
                    <ul>
                        <li class="nav-item">meno: admin</li>
                        <li class="nav-item">heslo: nknAdmin1kn#</li>
                    </ul>

                    <li class="nav-item"><b>Konfiguračný súbor</b></li>
                    <ul>
                        <li class="nav-item">cesta: <b>www\config\config.php</b></li>
                    </ul>

                    <li class="nav-item"><b><a href="https://github.com/mask1to/webtech2Final" target="_blank">Github repozitár</a></b></li>
                    <ul>
                        <li class="nav-item">repozitár obsahuje aj súbor README.md, v ktorom opisujeme správanie implementovanej aplikácie</li>
                    </ul>
                </ul>
            </div>

        </div>
    </div>

</div>

<?php
include "partials/footer.php";
?>

