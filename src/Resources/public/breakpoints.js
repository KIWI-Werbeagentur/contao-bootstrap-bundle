var Location =
    {
        breakpointsWizard: function (id) {
            var table = $(id),
                tbody = table.getElement('tbody'),
                makeSortable = function (tbody) {
                    var rows = tbody.getChildren(),
                        childs, i, j, select, input;

                    for (i = 0; i < rows.length; i++) {
                        childs = rows[i].getChildren();
                        for (j = 0; j < childs.length; j++) {
                            if (select = childs[j].getElement('select')) {
                                select.name = select.name.replace(/\[[0-9]+]/g, '[' + i + ']');
                            }
                            if (input = childs[j].getElement('input[type="text"]')) {
                                input.name = input.name.replace(/\[[0-9]+]/g, '[' + i + ']');
                            }
                        }
                    }

                    new Sortables(tbody, {
                        constrain: true,
                        opacity: 0.6,
                        handle: '.drag-handle',
                        onComplete: function () {
                            makeSortable(tbody);
                        }
                    });
                },
                addEventsTo = function (tr) {
                    var command, select, button, next, ntr, childs, cbx, i;
                    tr.getElements('button').each(function (bt) {
                        if (bt.hasEvent('click')) return;
                        command = bt.getProperty('data-command');

                        switch (command) {
                            case 'copy':
                                bt.addEvent('click', function () {
                                    Backend.getScrollOffset();
                                    ntr = new Element('tr');
                                    childs = tr.getChildren();
                                    for (i = 0; i < childs.length; i++) {
                                        next = childs[i].clone(true).inject(ntr, 'bottom');
                                        if (select = childs[i].getElement('select')) {
                                            next.getElement('select').value = select.value;
                                        }
                                        if (button = childs[i].getElementById('delete')) {
                                            next.getElement('button.delete').setProperty('data-command','delete').setStyle('filter','none').setStyle('cursor','pointer');
                                        }
                                    }
                                    ntr.inject(tr, 'after');
                                    ntr.getElements('.chzn-container').destroy();
                                    // console.log(ntr.getElements('select.tl_select'))
                                    ntr.getElements('select.tl_select').each(function(thisSelect) {
                                        new Chosen(thisSelect);
                                    });
                                    addEventsTo(ntr);
                                    makeSortable(tbody);
                                });
                                break;
                            case 'delete':
                                bt.addEvent('click', function () {
                                    Backend.getScrollOffset();
                                    if (tbody.getChildren().length > 1) {
                                        tr.destroy();
                                    }
                                    makeSortable(tbody);
                                });
                                break;
                            case 'enable':
                                bt.addEvent('click', function () {
                                    Backend.getScrollOffset();
                                    cbx = bt.getNext('input[type="checkbox"]');
                                    if (cbx.checked) {
                                        cbx.checked = '';
                                        bt.getElement('img').src = Backend.themePath + 'icons/invisible.svg';
                                    } else {
                                        cbx.checked = 'checked';
                                        bt.getElement('img').src = Backend.themePath + 'icons/visible.svg';
                                    }
                                    makeSortable(tbody);
                                });
                                break;
                            case null:
                                bt.addEvent('keydown', function (e) {
                                    if (e.event.keyCode == 38) {
                                        e.preventDefault();
                                        if (ntr = tr.getPrevious('tr')) {
                                            tr.inject(ntr, 'before');
                                        } else {
                                            tr.inject(tbody, 'bottom');
                                        }
                                        bt.focus();
                                        makeSortable(tbody);
                                    } else if (e.event.keyCode == 40) {
                                        e.preventDefault();
                                        if (ntr = tr.getNext('tr')) {
                                            tr.inject(ntr, 'after');
                                        } else {
                                            tr.inject(tbody, 'top');
                                        }
                                        bt.focus();
                                        makeSortable(tbody);
                                    }
                                });
                                break;
                        }
                    });
                };

            makeSortable(tbody);

            tbody.getChildren().each(function (tr) {
                addEventsTo(tr);
            });
        }
    };