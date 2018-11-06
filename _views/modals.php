<div id="modalComparador" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h3 id="myModalLabel3">Comparação de Texto</h3>
    </div>
    <div class="modal-body">
        <div class="span12 text-left" style="text-align: left; border:0px solid red;">
            <label><b>Texto 1</b></label>
            <textarea class="span12" rows="7" id="textarea1" ></textarea>
            <label><b>Texto 2</b></label>
            <textarea class="span12" rows="7" id="textarea2" ></textarea>
            <h1 id="equals"></h1>
        </div>
        <script>
            var string1 = document.getElementById('textarea1');
            var string2 = document.getElementById('textarea2');

            var equals = document.getElementById('equals');

            function isEquals() {
                if (string1.value === string2.value) {
                    equals.innerHTML = "É Igual";
                    equals.className = 'blue';
                }
                else {
                    equals.innerHTML = "É Diferente";
                    equals.className = 'blue';
                }
            }

            string1.onblur = function (e) {
                isEquals();
            };

            string2.onblur = function () {
                isEquals();
            };
            string1.onclick = function (e) {
                isEquals();
                this.select();
            };

            string2.onclick = function () {
                isEquals();
                this.select();
            };
            string1.onkeyup = function (e) {
                isEquals();
            };

            string2.onkeyup = function () {
                isEquals();
            };

            var table = document.getElementsByTagName('table');

            for (x = 0; x < table.length; ++x) {
                table[x].onclick = function () {
                    this.select();
                };
            }

        </script>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn blue">Fechar</button>
    </div>
</div>

<div class="modal hide fade" id="modalExecultarQuery">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Cabeçalho do modal</h3>
    </div>
    <div class="modal-body">
        <form id="formExeculteQuery" class="" method="post">
            <textarea rows="10" name="query" class="span12" style="max-width: 100%;"></textarea>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" data-dismiss="modal"  class="btn">Fechar</a>
        <button form="formExeculteQuery"  class="btn btn-primary" id="btnExeultarQuery">Execultar</button>
    </div>
</div>

<script>

</script>