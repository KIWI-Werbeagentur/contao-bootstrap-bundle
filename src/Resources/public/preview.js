let BootstrapPreview = class {
    static reload(select, breakpoint) {
        let arrBreakpoints = [
            'xs',
            'sm',
            'md',
            'lg',
            'xl',
            'xxl'
        ]
        let currentBreakpoint = arrBreakpoints.indexOf(breakpoint);

        let columns = select.value;

        let type = "";
        let isRowCols = false;

        if (select.name.indexOf('col') !== 0) {
            type = "_wrapper";
            isRowCols = true;
        }

        while (columns === 'inherit') {
            currentBreakpoint = currentBreakpoint-1;
            columns = document.getElementsByClassName('bs'+type+'_col_'+arrBreakpoints[currentBreakpoint])[0].querySelector('select').value;
        }

        let target = document.getElementById('ctrl_bootstrap_preview' + type).querySelector('.breakpoint.' + breakpoint);
        this.#changeColumns(columns, target, null, isRowCols);

        arrBreakpoints.some(function(el, i){
            // For all following breakpoints..
            if (i > arrBreakpoints.indexOf(breakpoint)) {
                // ...that have the option 'inherit', set the corresponding columns.
                if (document.getElementsByClassName('bs'+type+'_col_'+arrBreakpoints[i])[0].querySelector('select').value === 'inherit') {
                    target = document.getElementById('ctrl_bootstrap_preview'+type).querySelector('.breakpoint.' + arrBreakpoints[i]);
                    BootstrapPreview.#changeColumns(columns, target, null, isRowCols);
                } else {
                    return true;
                }
            }
        });
    };

    static #changeColumns(columns, el, container = null, isRowCols = false) {
        let html = '';

        let screenWidth = el.querySelector('#screen').attributes.width.value;
        let screenHeight = el.querySelector('#screen').attributes.height.value;

        if (columns !== 'none-only') {
            let containerWidth;
            let containerPosition;

            if (el.querySelector('#container')) {
                switch (container) {
                    case 'contained':
                        // not accurate, for visual reasons
                        containerWidth = (screenWidth / 4) * 3;
                        containerPosition = (screenWidth - containerWidth) / 2;
                        break;
                    case 'fluid':
                        containerWidth = screenWidth;
                        containerPosition = 0;
                        break;
                    default:
                        containerWidth = el.querySelector('#container').attributes.width.value;
                        containerPosition = el.querySelector('#container').attributes.x.value;
                }
            } else {
                containerWidth = screenWidth;
                containerPosition = 0;
            }

            html = `<rect id="container" x="${containerPosition}" y="0" width="${containerWidth}" height="${screenHeight}" />`;

            // Calculate columns
            const gridGutter = 30;
            if (columns === 'stretch') {
                html += `<g transform="translate(${containerPosition},0)">
                <rect id="col-${columns}" class="column" x="${gridGutter}" y="${gridGutter}" width="${containerWidth - gridGutter*2}" height="${screenHeight/3}" />
                    <g transform="translate(${gridGutter},${gridGutter})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="${containerWidth - gridGutter*2}" height="${screenHeight/3}" viewBox="0 0 200 30">
                    <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                    <stop offset="0" stop-color="#777"/>
                    <stop offset="1" stop-color="#666"/>
                    </linearGradient>
                <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
            </svg>
            </g>
            </g>
                <g transform="translate(${containerPosition},(${screenHeight/3}+${gridGutter}))">
                    <rect id="col-${columns}" class="column" x="${gridGutter}" y="${gridGutter}" width="${(containerWidth - gridGutter*3)/2}" height="${screenHeight/3}" />
                    <g transform="translate(${gridGutter},${gridGutter})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="${(containerWidth - gridGutter*3)/2}" height="${screenHeight/3}" viewBox="0 0 200 30">
                            <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                    <rect id="col-${columns}" class="column" x="${gridGutter + ((containerWidth - gridGutter)/2)}" y="${gridGutter}" width="${(containerWidth - gridGutter*3)/2}" height="${screenHeight/3}" />
                    <g transform="translate(${gridGutter + (containerWidth - gridGutter)/2},${gridGutter})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="${(containerWidth - gridGutter*3)/2}" height="${screenHeight/3}" viewBox="0 0 200 30">
                            <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                </g>`;

            }
            else if (columns === 'auto') {
                html += `<g transform="translate(${containerPosition},0)">
                <rect id="col-${columns}" class="column" x="${containerWidth/2 - (((containerWidth/2) - gridGutter*2)/2)}" y="${gridGutter}" width="${(containerWidth/2) - gridGutter*2}" height="${screenHeight/3}" />
                    <g transform="translate(${containerWidth/2 - (((containerWidth/2) - gridGutter*2)/2)},${gridGutter})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="${(containerWidth/2) - gridGutter*2}" height="${screenHeight/3}" viewBox="0 0 200 30">
                    <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                    <stop offset="0" stop-color="#777"/>
                    <stop offset="1" stop-color="#666"/>
                    </linearGradient>
                <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
            </svg>
            </g>
            </g>`;
            }
            else if (parseInt(columns)) {
                let cols = parseInt(columns);
                let strColumns = '';

                let colWidth;
                let colCount;

                if (isRowCols) {
                    colWidth = (containerWidth - gridGutter) / cols - gridGutter;
                    colCount = cols;
                } else {
                    colWidth = (containerWidth - gridGutter) / 12 * cols - gridGutter;
                    colCount = 12 / cols;
                }

                let previousWidths = parseInt(containerPosition);

                for (let i = 1; i <= colCount; i++) {
                    strColumns += `<rect id="col-${i}" class="column" x="${previousWidths + gridGutter}" y="${gridGutter}" width="${colWidth}" height="${screenHeight/3}" />`;
                    previousWidths = previousWidths + colWidth + gridGutter;
                }
                html += strColumns;
            }
        else if (columns === "umbruch") {
                let rowHeight = (screenHeight - gridGutter * 4)/3;
                html += `<g transform="translate(${containerPosition},0)">
                <rect id="col-${columns}" class="column" x="${gridGutter}" y="${gridGutter}" width="${(containerWidth - gridGutter*3)/2}" height="${rowHeight}" />
                    <g transform="translate((((${containerWidth}-${gridGutter}*3)/2)+${gridGutter}*2),${gridGutter})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="${(containerWidth - gridGutter*3)/2}" height="${rowHeight*2 + gridGutter}" viewBox="0 0 204 70">
                    <linearGradient id="a" x1="40.8" y1="14" x2="163.2" y2="56">
                    <stop offset="0" stop-color="#777"/>
                    <stop offset="1" stop-color="#666"/>
                    </linearGradient>
                <path fill="url(#a)" d="M 0 35
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            c 7 0 10 -5 10 -10
                            c 0 -5 -3 -10 -10 -10
                            l -15 0
                            l 0 -8
                            l 15 0
                            c 11 0 18 9 18 18
                            c 0 9 -7 18 -18 18
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
            </svg>
            </g>
                <rect id="col-${columns}" class="column" x="${gridGutter}" y="${gridGutter*2 + rowHeight}" width="${(containerWidth-gridGutter*3)/2}" height="${rowHeight}" />
            </g>`;
            }
            else if (columns === 'keinUmbruch') {
                let rowHeight = (screenHeight - gridGutter * 4)/3;
                html += `<g transform="translate(${containerPosition},0)">
                <rect id="col-${columns}" class="column" x="${gridGutter}" y="${gridGutter}" width="${(containerWidth - gridGutter*3)/2}" height="${rowHeight}" />
                    <rect id="col-${columns}" class="column" x="${gridGutter*2 + ((containerWidth - gridGutter*3)/2)}" y="${gridGutter}" width="${(containerWidth - gridGutter*3)/2}" height="${rowHeight}" />
                    <g transform="translate(${gridGutter},(${gridGutter}*2 + $rowHeight))">
                    <svg xmlns="http://www.w3.org/2000/svg" width="${(containerWidth - gridGutter*2)}" height="${rowHeight*2 + gridGutter}" viewBox="0 0 264 264">
                    <linearGradient id="b" x1="52.8" y1="52.8" x2="211.2" y2="211.2">
                    <stop offset="0" stop-color="#777"/>
                    <stop offset="1" stop-color="#666"/>
                    </linearGradient>
                <path fill="url(#b)" d="M 30 132
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            c 7 0 10 -5 10 -10
                            c 0 -5 -3 -10 -10 -10
                            l -15 0
                            l 0 -8
                            l 15 0
                            c 11 0 18 9 18 18
                            c 0 9 -7 18 -18 18
                            l -172 0 l 11 11
                            l -10 0
                            z
                            M 0 132
                            c 0 -72 60 -132 132 -132
                            s 132 60 132 132
                            l -20 0
                            c 0 -41 -16 -69 -40 -87
                            l -120 187
                            c 57 35 157 -4 160 -100
                            l 20 0
                            c 0 72 -60 132 -132 132
                            s -132 -60 -132 -132
                            l 20 0
                            c 1 40 18 68 46 88
                            l 121 -186
                            c -64 -38 -167 1 -167 98
                            z"/>
            </svg>
            </g>
            </g>`;
            }
        } else {
            let eye = `<g transform="translate(${((screenWidth/3)/2)},((${((screenHeight/3)/2)})"><svg xmlns="http://www.w3.org/2000/svg" width="${((screenWidth/3)*2)}" height="${((screenHeight/3)*2)}" viewBox="0 0 500 500">
            <linearGradient id="a" x1="101.792" y1="101.792" x2="398.209" y2="398.209">
                <stop offset="0" stop-color="#777"/>
                <stop offset="1" stop-color="#666"/>
                </linearGradient>
            <path fill="url(#a)"
                  d="M250 90.812C111.93 90.812 0 162.082 0 250s111.93 159.188 250 159.188S500 337.918 500 250 388.07 90.812 250 90.812zm47.572 57.15l-40.872 40.87c-3.957 3.958-10.434 3.958-14.392 0l-40.89-40.89c15.353-2.063 31.41-3.178 47.958-3.178 16.632 0 32.773 1.115 48.196 3.198zM52.882 250c0-45.06 52.882-83.494 127.212-98.497L145.5 186.1c-3.96 3.956-3.944 10.418.032 14.357l42.774 42.38c3.976 3.94 3.99 10.4.033 14.358L145.76 299.77c-3.958 3.96-3.958 10.436 0 14.393l34.333 34.334c-74.328-15.004-127.21-53.44-127.21-98.497zm196.495 105.235c-16.55 0-32.605-1.114-47.96-3.18l40.892-40.89c3.957-3.958 10.434-3.958 14.39 0l40.874 40.872c-15.424 2.084-31.567 3.198-48.197 3.198zm191.65-81.932c-13.83 32.763-56.197 59.726-112.377 73.01-3.25.815-6.533 1.564-9.833 2.28l34.43-34.43c3.957-3.957 3.957-10.434 0-14.392l-42.577-42.574c-3.957-3.96-3.957-10.435 0-14.394l42.576-42.575c3.96-3.96 3.96-10.435 0-14.393l-34.427-34.428c3.3.715 6.583 1.465 9.834 2.28 56.18 13.284 98.547 40.247 112.375 73.013 3.682 7.75 5.55 15.553 5.55 23.3s-1.868 15.552-5.552 23.303z"/>
        </svg></g>`;
            html += eye;
        }
        el.querySelector('#viewport').querySelectorAll(':not(#screen)').forEach(
            function (currentValue) {
                currentValue.parentNode.removeChild(currentValue);
            }
        );
        el.querySelector('#viewport').insertAdjacentHTML('beforeend', html);
    }
};