var _fNome, _fSize, _fTipo, _fLastModifiedDate;
var _fFile = new Object();


//=======================================
//      ESTE PLUGIN VALIDA DATAS
//=======================================
(function($) {

    $.fn.validarData = function(options) {

        //O elemento princial, sem funcionalidades (kkk)
        var elemento = $(this);
        /**
         * <p>Definições padrões</p>
         * @type type
         */
        var defaults = {
            t_mascara: '##/##/####',
            t_regex: /(^(((0[1-9]|[12][0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)/gmi,
            t_permitir_ano_anterior: false
        };

        //Função do JQuery, extends
        var setting = $.extend({}, defaults, options);

        //Função para mensagens
        function mensagens(mensagem) {
            alert(mensagem);
            elemento.select();
            elemento.val("");
        }

        //Retorna o próprio elemento
        return this.each(function() {

            //Insere a máscara
            $(this).mask(setting.t_mascara);

            //Evento ao sair do elemento
            $(this).blur(function() {

                //Neste ponto se o valor do objeto t_permitir_ano_anterior= false então não é permitido colocar 
                //anos anteriores a este.
                if (setting.t_permitir_ano_anterior === false) {

                    var div = $(this).val().split('/');

                    var data = new Date();
                    //Exemplo: ano do input for menor que o ano do computador do cliente
                    if (div[2] < data.getFullYear()) {
                        mensagens('O ano não pode ser menor que ' + data.getFullYear());
                        return false;
                    }
                }
                /*
                 //Faz uma regular expressão para validar a entrada do usuário
                 var patt = new RegExp(setting.t_regex);
                 
                 //Testa esta entrada com a string do input
                 var res = patt.test($(this).val());
                 //Testa esta entrada final, com a saída para o cliente
                 if (res === false) {
                 mensagens("Data Inválida.");
                 }
                 */
            });

        });

    };

}(jQuery));


(function($) {

    var f = false;
    /**
     * 
     * @param {type} texto
     * @returns {plugin.select_L25.$.fn@call;each}
     */
    $.fn._neoPluginSelect = function(texto) {

        var elemt = $(this);

        if (texto == undefined || texto == "") {
            texto = "Selecione...";
        }

        var defaults = {
            border: '1px solid #4d90fe'
        };

        var btnId = "btn" + Math.floor((Math.random() * 100) + 1);

        var buttons = "<a id='" + btnId + "' class='btn blue'><i class='icon-square'></i> Selecionar Todos</a><br/>";

        elemt.prepend(buttons);

        var _b = $("#" + btnId);

        _b.addClass("btn").addClass("").addClass("red");

        var _select = $(this).find("select");

        _select.attr('multiple', true);

        var novoId = "neoInovatSelect" + Math.floor((Math.random() * 100) + 1);

        _select.attr("id", novoId).css('border', defaults.border);

        return this.each(function() {

            var option = document.createElement("optgroup");
            option.label = texto;
            option.text = "";
            _select.prepend(option);

            elemt.css("border", "0px solid red");

            _b.click(function() {
                if (f == false) {
                    $('#' + novoId + ' option').attr('selected', true);
                    _b.html("<i class='icon-check-square'></i> Remover Todos ");
                    f = true;
                }
                else {
                    $('#' + novoId + ' option').attr('selected', false);
                    _b.html("<i class='icon-square'></i> Selecionar Todos");
                    f = false;
                }
            });

        });

    };
}(jQuery));

/**
 * 
 * @param {type} value
 * @param {type} array
 * @returns {Boolean}
 */
function in_array(value, array) {
    return (array.indexOf(value) !== -1);
}

/**
 * Plugin neoUpload
 * @param {type} $
 * @returns {undefined}
 */
(function($) {
    /**
     * 
     * @param {type} options
     * @returns {plugin.select_L77.$.fn@call;each}
     */
    $.fn._neoPluginUpload = function(options) {

        var elem = $(this);

        isValido = true;

        elem.attr("title", "Clique para adicionar um arquivo");

        inputElement = fileName = undefined;

        /**
         * 
         * @param {type} value
         * @param {type} array
         * @returns {Boolean}
         */
        function in_array(value, array) {
            return (array.indexOf(value) !== -1);
        }

        //Definições Defaults
        var defaults = {
            name: "file",
            id: "file",
            mensagem: {
                incluir: "Clique para incluir um arquivo",
                alterar: "Clique para alterar este arquivo"
            },
            evenChange: function(e) {
                e = isValido;
            },
            retornaInputElement: function() {
            },
            accept: ".pdf,.docx",
            tiposPermitidos: ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'video/mp4',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/force-download'],
            tamanhoPermitido: 2097152 //2MB
        };

        function bytesToSize(bytes) {
            if (bytes < 1024)
                return Math.round(bytes) + " Bytes";
            else if (bytes < 1048576)
                return Math.round((bytes / 1024).toFixed(3)) + " KB";
            else if (bytes < 1073741824)
                return Math.round((bytes / 1048576).toFixed(3)) + " MB";
            else
                return Math.round((bytes / 1073741824).toFixed(3)) + " GB";
        }

        var validaUpload = function(element, _defts) {
            var txt = "";
            if ('files' in element) {
                if (element.files.length == 0) {
                    txt = "Selecione um ou mais arquivos";
                } else {
                    for (var i = 0; i < element.files.length; i++) {
                        txt += "<br><strong>" + (i + 1) + ". file</strong><br>";
                        var file = element.files[i];
                        if ('name' in file) {
                            txt += "name: " + file.name + "<br>";
                        }
                        //Type
                        console.log('Tipo:' + file.type);
                        if ('type' in file) {
                            if (in_array(file.type, _defts.tiposPermitidos) === false) {
                                element.value = '';
                                alert("O tipo do arquivo não é permitido.\n\nNome: " + file.name +
                                        "\nTamanho: " + bytesToSize(file.size));
                                isValido = false;
                                return false;
                            }
                            else {
                                isValido = true;
                            }
                        }
                        if ('size' in file) {
                            if (file.size > _defts.tamanhoPermitido) {
                                element.value = '';
                                alert("O tamanho do arquivo excede o tamanho permitido.\n\nNome: " + file.name +
                                        "\nTamanho: " + bytesToSize(file.size));
                                isValido = false;
                                return false;
                            }
                            else {
                                isValido = true;
                            }
                            txt += "size: " + bytesToSize(file.size) + "\n";
                        }
                    }
                }
            }
            else {
                if (element.value == "") {
                    txt += "Selecione um ou mais arquivos.";
                } else {
                    txt += "A propriedade file não é suportada neste navegador";
                    txt += "<br>The path of the selected file: " + element.value;
                }
            }
            return txt;
        };


        var setting = $.extend({}, defaults, options);

        return this.each(function() {

            var _id = 'uploadFile' + Math.floor((Math.random() * 100) + 1);

            var label1 = "<label for='" + _id + "' class='neo_label_upload bold' ></label>";

            var label2 = "<label for='" + _id + "'class='neo_label_filename bold' ></label>";

            var file = "<input type='file' accept='" + setting.accept + "'  name='" + setting.name + "' id='" + _id + "' />";

            elem.append([label2, label1, file]);

            inputElement = elem.find("input[type='file']");
            inputElement.hide();

            if (elem.attr("class") !== "upload") {
                elem.addClass("upload");
            }

            inputElement.change(function(isVal) {
                validaUpload(this, setting);
                fileName = $(this).val().split('\\').pop();
                $(".neo_label_filename").html('<span class="text-info" style=\'left:0px;text-align:left;top:2px;\'>' + fileName + '</span>');
                isVal = isValido;
                return setting.evenChange(fileName, isVal);
            });

            inputElement.change(function(ev) {
                var fileInfo = ev.target.files;
                if (ev.target.value == '') {
                    _fTipo = _fNome = _fSize = _fLastModifiedDate = undefined;
                } else {
                    _fTipo = fileInfo[(ev.target.files.length - 1)].type;
                    _fNome = fileInfo[(ev.target.files.length - 1)].name;
                    _fSize = fileInfo[(ev.target.files.length - 1)].size;
                    _fLastModifiedDate = fileInfo[(ev.target.files.length - 1)].lastModifiedDate;

                    if (_fTipo == '') {
                        var getExt = _fNome.split('.').pop();
                        _fTipo = 'O tipo é desconhecido. Mas sua extensão é: ' + getExt;
                    }
                    _fFile['name'] = _fNome;
                    _fFile['type'] = _fTipo;
                    _fFile['size'] = bytesToSize(_fSize) + '(' + _fSize + ' bytes)';
                    _fFile['lastModifiedDate'] = _fLastModifiedDate;
                }

            });

            setting.retornaInputElement(inputElement);

            elem.find(".neo_label_upload").mouseover(function() {
                $(this).html("<span class='text-info' style='position:absolute;left:0px;text-align:left;top:2px;'>\n\Clique para escolher...<span>");
            }).mouseout(function() {
                $(this).html("<span class='text-info'><span>");
            });

        });
    };
}(jQuery));

/**
 * Plugin plugin_alert
 * @param {type} $
 * @returns {undefined}
 */
(function($) {

    /**
     * 
     * @param {type} options
     * @returns {plugin.select_L158.$.fn@call;each}
     */
    $.fn._neoPluginAlert = function(options) {

        var elem = $(this);
        var _html, mensagem;

        var defaults = {
            mensagem: "Mensagem...",
            tipoCor: "warning", //Prováveis: warning, success, info e error
            icon: "icon-exclamation-sign", // icon-info-sign,icon-exclamation-sign,icon-warning-sign, icon-ban-circle, icon-question-sign
            tamanho: "medio",
            link: "./?exe=view",
            btnCloseVisivel: 'close'
        };

        function Main(def) {

            if (elem.text() == '' && def.mensagem != 'Mensagem...')
            {
                mensagem = def.mensagem;
            }
            else if (elem.text() != '' && def.mensagem == 'Mensagem...') {
                mensagem = elem.html();
            }
            else if (elem.text() == '' && def.mensagem != 'Mensagem...') {
                mensagem = def.mensagem;
            }
            else {
                mensagem = elem.html();
            }

            if (def.tamanho == "grande") {

                _html = "<div class='row-fluid'><div class='span12'><div class='alert alert-block alert-" + def.tipoCor + " fade in' style = 'padding:10px 25px;' ><a href='" + def.link + "' class='" + def.btnCloseVisivel + "' data-dismiss='alert'></a><br><h4 class='alert-heading' style='padding:0px;margin:0px;font-size: 20px;line-height:30px;' ><i class='" + def.icon + "'></i>&nbsp" + mensagem + " </h4><br/></div></div></div>";
            }
            else if (def.tamanho == "medio") {
                _html = "<div class='row-fluid'><div class='span2'></div><div class='span8'><div class='alert alert-block alert-" + def.tipoCor + " fade in' style = 'padding:10px 25px;' ><a href='" + def.link + "' class='" + def.btnCloseVisivel + "' data-dismiss='alert'></a><br><h4 class='alert-heading' style='padding:0px;margin:0px;font-size: 20px;line-height:30px;' ><i class ='" + def.icon + "'></i>&nbsp" + mensagem + "</h4><br/></div></div><div class='span2'></div></div>";
            }
            else {
                _html = "<div class='row-fluid'><div class='span3'></div><div class='span6'><div class='alert alert-block alert-" + def.tipoCor + " fade in' style = 'padding:10px 25px;' ><a href='" + def.link + "' class='" + def.btnCloseVisivel + "' data-dismiss='alert'></a><br><h4 class='alert-heading' style='padding:0px;margin:0px;font-size: 20px;line-height:30px;' ><i class ='" + def.icon + "'></i>&nbsp" + mensagem + "</h4><br/></div></div><div class='span3'></div></div>";
            }
        }

        //Main(defaults);

        var settings = $.extend({}, defaults, options);

        return this.each(function() {
            Main(settings);
            elem.html(_html);
        });
    };
}(jQuery));

/**
 * Verifica se a data do usuário é menor que a data local
 * @param {type} d A data do usuário
 * @param {type} h A hora do usuáiro, geralmente um input com.
 * @returns {Boolean} Retorna true se a data do usuário for menor ou igual a do sistema.
 */
function varificarData(d, h) {

    var data = $(d).val().split("/");

    var hora = $(h).val().split(":");

    var userDate = new Date(data[2], (data[1]), data[0], hora[0], hora[1]);

    var systemData = new Date();

    var arrS = systemData.toLocaleDateString().split("/");
    var hArr = systemData.toLocaleTimeString().split(":");

    var novaData = new Date(arrS[2], arrS[1], arrS[0], hArr[0], hArr[1]); //Ano, mes, dias, hora, minuto, seg

    return (userDate.getTime() <= novaData.getTime()) ? true : false;
}

/**
 * Verifica se a data do usuário é menor que a data local
 * @param {type} d A data do usuário
 * @returns {Boolean} Retorna true se a data do usuário for menor ou igual a do sistema.
 */
function dataIsMenorAsString(d) {

    var data = d.split("/");

    var systemData = new Date();

    var dataDoUsuario = new Date(data[2], (data[1]), data[0], systemData.getHours(), systemData.getMinutes());

    var arrS = systemData.toLocaleDateString().split("/");

    var hArr = systemData.toLocaleTimeString().split(":");

    var novaData = new Date(arrS[2], arrS[1], arrS[0], hArr[0], hArr[1]); //Ano, mes, dias, hora, minuto, seg

    return (dataDoUsuario.getTime() <= novaData.getTime()) ? true : false;
}



//Adiciona zero em números < 10
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}


(function($) {

    /**
     * <p>Botão para voltar</p>
     * @param {type} options
     * @returns {plugins_neoinovat_L351.$.fn@call;each}
     */
    $.fn._neoPluginBtnVoltar = function(options) {

        //Elemento principail
        var elem = $(this);

        /**
         * Defaults Values
         * @type type
         */
        var defaults = {
            texto: "Voltar",
            cor: "green",
            tamanho: "",
            direcao: "../",
            hrVisible: true,
            float: "float"
        };

        var settings = $.extend({}, defaults, options);

        return this.each(function() {

            elem.addClass("btn " + settings.cor + " " + settings.tamanho + " ")
                    .css("float", settings.float)
                    .attr({href: settings.direcao})
                    .text(settings.texto)
                    .prepend("<i class='icon-arrow-left'></i>&nbsp;");

            if (settings.hrVisible === true) {
                // elem.append("<hr style='border:0;border-top:1px solid #ccc' />");
            }
        });

    };
}(jQuery));


(function($) {

    /**
     * <p>Modal</p>
     * @param {type} options
     * @returns {plugins_neoinovat_L373.$.fn@call;each}
     */
    $.fn._neoPluginModal = function(options) {

        //Elemento principail
        var elem = $(this);
        var closeWithESC = false;
        /**
         * Defaults Values
         * @type type
         */
        var defaults = {
            texto: "Voltar"
        };


        var settings = $.extend({}, defaults, options);

        return this.each(function() {

            /**
             * Fecha o modal inicialmente quando a págian é carregada
             */
            if (options === undefined || options === '' || options === 'hide') {
                elem.hide();
                closeWithESC = false;
            }
            else if (options === 'show') {//Mostra o modal
                elem.show();
                closeWithESC = true;
            }

            /**
             * Esta classe serve para abrir o modal
             */
            $('.abrir-modal').click(function() {
                var modalId = $(this).attr('href');
                closeWithESC = true;
                $(modalId).show();
                return false;
            });

            /**
             * Se o usuário clicar em abrir modal
             * Pressionando a tecla ESC do teclado para fechar o modal
             * @param {type} e
             * @returns {undefined}
             */

            window.onkeyup = function(e) {
                if (closeWithESC == true) {
                    if (e.keyCode === 27) {
                        elem.hide();
                    }
                }
            }

            /**
             * Pesquisa pelo elemento bottão com o atributo [aria-hidden='true']
             * Serve para fechar o modal
             */
            elem.find(".modal-header [aria-hidden='true']").click(function() {
                closeWithESC = false;
                elem.hide();
            });
            /**
             * Pesquisa pelo elemento bottão com o atributo [data-close-modal='true']
             * Serve para fechar o modal
             */
            elem.find(".neoinovat-modal-footer [data-close-modal='true']").click(function() {
                closeWithESC = false;
                elem.hide();
            });

        });
    };
}(jQuery));




//===================================================
//          Pega as informações de um arquivo        
//          @type @arr;fileInfo@pro;name             
//===================================================

(function($) {
    $.fn._neoFileInfo = function() {

        var _element = $(this);

        return this.each(function() {

            function bytesToSize(bytes) {
                if (bytes < 1024)
                    return Math.round(bytes) + " Bytes ";
                else if (bytes < 1048576)
                    return Math.round((bytes / 1024).toFixed(3)) + " KB ";
                else if (bytes < 1073741824)
                    return Math.round((bytes / 1048576).toFixed(3)) + " MB ";
                else
                    return Math.round((bytes / 1073741824).toFixed(3)) + " GB ";
            }

            _element.change(function(e) {
                var fileInfo = e.target.files;
                _fTipo = fileInfo[(e.target.files.length - 1)].type;
                _fNome = fileInfo[(e.target.files.length - 1)].name;
                _fSize = fileInfo[(e.target.files.length - 1)].size;
                _fLastModifiedDate = fileInfo[(e.target.files.length - 1)].lastModifiedDate;

                if (_fTipo == '') {
                    var getExt = _fNome.split('.').pop();
                    _fTipo = 'O tipo é desconhecido. Mas sua extensão é: ' + getExt;
                }
                _fFile['name'] = _fNome;
                _fFile['type'] = _fTipo;
                _fFile['size'] = bytesToSize(_fSize) + '(' + _fSize + ' bytes)';
                _fFile['lastModifiedDate'] = _fLastModifiedDate;

            });

        });

    };
}(jQuery));


var handleSidebarToggler = function() {

    var container = $(".page-container");

    if ($.cookie('sidebar-closed') == 1) {
        container.addClass("sidebar-closed");
    }

    // handle sidebar show/hide
    $('.page-sidebar .sidebar-toggler').click(function() {
        $(".sidebar-search").removeClass("open");
        var container = $(".page-container");
        if (container.hasClass("sidebar-closed") === true) {
            container.removeClass("sidebar-closed");
            $.cookie('sidebar-closed', null);
        } else {
            container.addClass("sidebar-closed");
            $.cookie('sidebar-closed', 1);
        }
    });

    // handle the search bar close
    $('.sidebar-search .remove').click(function() {
        $('.sidebar-search').removeClass("open");
    });
};


//Valida uma string como JSON
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

handleSidebarToggler();


(function($) {

    /**
     * <p>Botão para voltar</p>
     * @param {type} options
     * @returns {plugins_neoinovat_L351.$.fn@call;each}
     */
    $.fn._neoBeforeUnload = function(editar, mensagem, options) {

        estaEditando = false;
        /**
         * Defaults Values
         * @type type
         */
        var defaults = {
            texto: "Voltar"
        };

        var settings = $.extend({}, defaults, options);

        return this.each(function() {

            $('input,textarea').keyup(function() {
                estaEditando = true;
            });

            if (mensagem === undefined) {
                mensagem = "Cemvs";
            }
            window.onbeforeunload = function() {
                if (estaEditando === true && editar === false || options != undefined) {
                    return mensagem;
                }
            };

        });

    };
}(jQuery));

function titleize(text) {
    var words = text.toLowerCase().split(" ");
    for (var a = 0; a < words.length; a++) {
        var w = words[a];
        words[a] = w[0].toUpperCase() + w.slice(1);
    }
    return words.join(" ");
}