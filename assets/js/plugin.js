(function($) {
    $.fn.IvanSearch = function(options) {

        var funcCaller = [];
        var getIDs = [];
        var getVal = [];

        var functionCaller = false;
        var getID = false;
        var settings = {
            'dataModel': [],
            'maxcounter': 10,
            'defaultValue': [],
            'multisearch': false
        };

        if (options) {
            var p = $.extend(settings, options);
        }

        this.setMainButton = function() {
            var that = this;
            var _rowControl = $('<div class="form-row row-fluid"></div>');
            var _mainButton = $('<div class="span3"><div>');
            var _subFormRow = $('<div class="row-fluid"></div>');
            var _btnGroup = $('<div class="btn-group"></div>');
            _btnGroup.append('<button class="btn btn-primary " id="button_search" type="button"><i class="icon-search icon-white"></i> Search</button>');
            _btnGroup.append('<button class="btn btn-success" id="reset_search" type="button"><i class="icon-refresh icon-white"></i> Reset</button>');
            _subFormRow.append(_btnGroup);
            _mainButton.append(_subFormRow);
            _rowControl.append(_mainButton);

            return _rowControl;
        }

        this.getCounterRow = function() {
            return $('div.row_search').length;
        }

        this.reDraw = function() {
            var that = this;
            var counter = 0;

            $('.ivansearch_field').each(function() {
                $(this).removeAttr("id")
                $(this).attr('id', 'ivansearch_field_' + counter);
                counter++;
            });

            counter = 0;
            $('.ivansearch_ops').each(function() {
                $(this).removeAttr("id")
                $(this).attr('id', 'ivansearch_ops_' + counter);
                counter++;
            });

            counter = 0;
            $('.ivansearch_val').each(function() {
                $(this).removeAttr("id")
                $(this).attr('id', 'ivansearch_val_' + counter);
                counter++;
            });

            counter = 0;
            $('.ivansearch_button').each(function() {
                $(this).removeAttr("id")
                $(this).attr('id', 'ivansearch_btn_' + counter);
                counter++;
            });

            counter = 0;
            $('.row_search').each(function() {
                $(this).removeAttr("id")
                $(this).attr('id', 'row_search_' + counter);
                counter++;
            });

            counter = 0;
            $('.row-vals').each(function() {
                $(this).removeAttr("id")
                $(this).attr('id', 'row-val-' + counter);
                counter++;
            });

            that.initialFields();
        }

        this.CreateTableRow = function(index, defVal) {
            var that = this;
            var _rowControl = $('<div class="form-row row-fluid row_search" id="row_search_' + index + '"></div>');

            _rowControl.append(that.CreateFieldNames(index, defVal));
            _rowControl.append(that.CreateFieldOps(index, defVal));
            _rowControl.append(that.CreateFieldVals(index, defVal));
            _rowControl.append(that.CreateFieldControls(index));
            return _rowControl;
        }

        this.CreateFieldNames = function(index, defVal) {
            var that = this;
            var _fieldname = $('<div class="span3"><div>');
            var _subFormRow = $('<div class="row-fluid"></div>');
            var _component;


            if (p.multisearch === true) {
                _component = $('<select class="span12 ivansearch_field" id="ivansearch_field_' + index + '" name="cols[]"><option></option></select>');

                if (defVal != '') {
                    for (i = 0; i < p.dataModel.length; i++) {
                        if (p.dataModel[i].value === defVal) {
                            _component.append($('<option></option>').val(p.dataModel[i].value).html(p.dataModel[i].text).attr('selected', true));
                        } else {
                            _component.append($('<option></option>').val(p.dataModel[i].value).html(p.dataModel[i].text));
                        }
                    }
                } else {
                    for (i = 0; i < p.dataModel.length; i++) {
                        _component.append($('<option></option>').val(p.dataModel[i].value).html(p.dataModel[i].text));
                    }
                }
            } else {
                _component = $('<select class="span12 ivansearch_field" id="ivansearch_field_' + index + '" name="cols[]"></select>');
                _component.append($('<option></option>').val(p.defaultValue[index].value).html(p.defaultValue[index].text).attr('selected', true));
            }
            _subFormRow.append(_component);
            _fieldname.append(_subFormRow);
            return _fieldname;
        }

        this.CreateFieldOps = function(index, defVal) {
            var that = this;
            var _fieldop = $('<div class="span1"><div>');
            var _subFormRow = $('<div class="row-fluid"></div>');
            var _component;

            if (p.multisearch === true) {
                _component = $('<select class="span12 ivansearch_ops" id="ivansearch_ops_' + index + '" name="ops[]"><option></option></select>');

                if (defVal != '') {
                    for (i = 0; i < p.defaultValue.length; i++) {
                        if (defVal === p.defaultValue[i].value) {
                            for (x = 0; x < p.dataModel.length; x++) {
                                if (defVal === p.dataModel[x].value) {
                                    for (y = 0; y < p.dataModel[x].ops.length; y++) {
                                        if (p.defaultValue[i].ops == p.dataModel[x].ops[y]) {
                                            _component.append($('<option></option>').val(p.dataModel[x].ops[y]).html(p.dataModel[x].ops[y]).attr('selected', true));
                                        } else {
                                            _component.append($('<option></option>').val(p.dataModel[x].ops[y]).html(p.dataModel[x].ops[y]));
                                        }
                                    }
                                    break;
                                }
                            }
                            break;
                        }
                    }
                } else {
                    _component = $('<select class="span12 ivansearch_ops" id="ivansearch_ops_' + index + '" name="ops[]"><option></option></select>');
                }
            } else {
                _component = $('<select class="span12 ivansearch_ops" id="ivansearch_ops_' + index + '" name="ops[]"></select>');
                _component.append($('<option></option>').val(p.defaultValue[index].ops).html(p.defaultValue[index].ops).attr('selected', true));
            }
            _subFormRow.append(_component);
            _fieldop.append(_subFormRow);
            return _fieldop;
        }

        this.CreateFieldVals = function(index, defVal) {
            var that = this;
            var _fieldval = $('<div class="span3"><div>');
            var _subFormRow = $('<div class="row-fluid row-vals" id="row-val-' + index + '"></div>');
            var _component;

            if (defVal != '') {
                for (i = 0; i < p.defaultValue.length; i++) {
                    if (defVal === p.defaultValue[i].value) {
                        for (x = 0; x < p.dataModel.length; x++) {
                            if (defVal === p.dataModel[x].value) {
                                switch (p.dataModel[x].type) {
                                    case "currency":
                                        _component = $('<input type="text" class="span12 text text-right ivansearch_val" id="ivansearch_val_' + index + '"  name="vals[]" />');
                                        _component.number(true, 2);
                                        break;

                                    case "date":
                                        _component = $('<input type="text" class="span12 text ivansearch_val" id="ivansearch_val_' + index + '" name="vals[]"/>');
                                        _component.datepicker({
                                            //showOn: "button",
                                            //buttonImage: "calendar.gif",
                                            dateFormat: 'yy-mm-dd',
                                            buttonImageOnly: true,
                                            changeMonth: true,
                                            changeYear: true
                                        });
                                        break;

                                    case "custom":
                                        _component = $('<div class="span12 text ivansearch_val" id="ivansearch_val_' + index + '" ></div>');

                                        getIDs.push(index);
                                        funcCaller.push(p.dataModel[x].callBack);
                                        getVal.push(p.defaultValue[i].defvalue);
                                        break;

                                    default:
                                        _component = $('<input type="text" class="span12 text ivansearch_val" id="ivansearch_val_' + index + '" name="vals[]" />');
                                        break;
                                }
                                break;
                            }
                        }
                        break;
                    }
                }
            } else {
                _component = $('<input type="text" class="span12 text ivansearch_val" id="ivansearch_val_' + index + '" name="vals[]"/>');
            }

            _subFormRow.append(_component);
            _fieldval.append(_subFormRow);
            return _fieldval;
        }

        this.CreateFieldControls = function(index) {
            var that = this;
            var _fieldControl = $('<div class="span1"><div>');
            var _subFormRow = $('<div class="row-fluid"></div>');
            var _btnControl;

            if (p.multisearch === true) {
                if (index == 0) {
                    _btnControl = $('<button type="button" class="btn ivansearch_button" id="ivansearch_btn_' + index + '" ></button>');
                    _btnControl.append('<i class="icon-plus"></i>');
                    _btnControl.bind('click', function() {
                        var _counterRow = that.getCounterRow();
                        if (p.maxcounter > _counterRow) {
                            $('.body_search').append(that.CreateTableRow(_counterRow, ''));
                            that.reDraw();
                        } else {
                            alert('Jumlah Row Searching Telah Mencapai Jumlah Maksimum');
                        }
                    });
                } else {
                    _btnControl = $('<button type="button" class="btn ivansearch_button" id="ivansearch_btn_' + index + '" ></button>');
                    _btnControl.append('<i class="icon-minus"></i>');
                    _btnControl.bind('click', function() {
                        var rowDeleteId = $(this).parent().parent().parent().attr('id');
                        $('#' + rowDeleteId).remove();
                        that.reDraw();
                    });
                }
            }
            _subFormRow.append(_btnControl);
            _fieldControl.append(_subFormRow);
            return _fieldControl;
        }

        this.setValueFields = function() {
            var that = this;
            var _bodysearch = $('<div class="body_search"></div>');

            var counter = (p.defaultValue.length > 0) ? p.defaultValue.length : 3;

            for (var j = 0; j < counter; j++) {
                if (p.defaultValue.length > 1) {
                    _bodysearch.append(that.CreateTableRow(j, p.defaultValue[j].value));
                } else {
                    _bodysearch.append(that.CreateTableRow(j, ""));
                }
            }

            that.append(_bodysearch);
            that.append(that.setMainButton());

            if (getIDs.length > 0) {
                for (var i = 0; i < getIDs.length; i++) {
                    //alert(getVal[i]);
                    functionCaller = self[funcCaller[i]];
                    functionCaller(getIDs[i], getVal[i]);
                    functionCaller = false;
                }
            }
        }

        this.initialFields = function() {
            var that = this;
            $('.ivansearch_field').each(function() {
                var n = $('.ivansearch_field').index(this);
                this.idx = n;
            }).change(function() {
                var n = this.idx;
                var nilai = $('#ivansearch_field_' + n).val();
                var fieldValue = $('#ivansearch_val_' + n);
                $('#ivansearch_ops_' + n + ' option').remove();

                if (nilai != "") {
                    for (i = 0; i < p.dataModel.length; i++) {
                        if (nilai == p.dataModel[i].value) {
                            for (x = 0; x < p.dataModel[i].ops.length; x++) {
                                $('#ivansearch_ops_' + n).append($('<option></option>').val(p.dataModel[i].ops[x]).html(p.dataModel[i].ops[x]))
                            }

                            switch (p.dataModel[i].type) {
                                case "currency":
                                    fieldValue.remove();
                                    $('#row-val-' + n).append('<input type="text" class="span12 text ivansearch_val text-right" id="ivansearch_val_' + n + '" name="vals[]"/>');
                                    $('#ivansearch_val_' + n).number(true, 2);
                                    $('#ivansearch_val_' + n).focus();
                                    break;
                                case "date":
                                    fieldValue.remove();
                                    $('#row-val-' + n).append('<input type="text" class="span12 text ivansearch_val" id="ivansearch_val_' + n + '" name="vals[]"/>');
                                    $('#ivansearch_val_' + n).datepicker({
                                        //showOn: "button",
                                        //buttonImage: "calendar.gif",
                                        dateFormat: 'yy-mm-dd',
                                        buttonImageOnly: true,
                                        changeMonth: true,
                                        changeYear: true
                                    });
                                    $('#ivansearch_val_' + n).focus();
                                    break;
                                case "custom":
                                    var functionCaller = false;
                                    functionCaller = self[p.dataModel[i].callBack];
                                    fieldValue.remove();
                                    $('#row-val-' + n).append('<div class="span12 ivansearch_val" id="ivansearch_val_' + n + '" ></div>');
                                    functionCaller(n);
                                    break;
                                default:
                                    fieldValue.remove();
                                    $('#row-val-' + n).append('<input type="text" class="span12 text ivansearch_val" id="ivansearch_val_' + n + '" name="vals[]"/>');
                                    $('#ivansearch_val_' + n).focus();
                                    break;
                            }
                            break;
                        }
                    }
                } else {
                    fieldValue.remove();
                    $('#row-val-' + n).append('<input type="text" class="span12 text ivansearch_val" id="ivansearch_val_' + n + '" name="vals[]"/>');
                    $('#ivansearch_val_' + n).focus();
                }
            });
        }

        this.setValueFields();
        this.initialFields();

    }
})(jQuery);