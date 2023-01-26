let currenciesToWatch = [];
let currenciesToRefresh = [];
let refreshInterval = 30;
const refreshIntervalInput = document.getElementById('refresh-interval');
refreshIntervalInput.value = refreshInterval;

let interval = setInterval(() => {
    getCurrencies();
}, refreshInterval * 60000);

function changeInterval() {
    clearInterval(interval);
    interval = setInterval(() => {
        getCurrencies();
    }, refreshInterval * 60000);
}

async function getCurrencies() {
    const loader = document.getElementById('loader');
    const tableBody = document.getElementById('tableBody');
    loader.classList.remove('close');

    return await fetch('./refresh.php')
        .then(response => {
            return response.text()
        })
        .then(res => {
            let items = [];
            if (res) {
                items = JSON.parse(res);
                currenciesToWatch = [...items.map((item) => ({...item, visible: true}))];
                currenciesToRefresh = [...items.map((item) => ({...item, visible: !!+item.isRefresh}))];
            }
            formTableBody(currenciesToWatch)
            formCurrenciesSelect()
        })
        .catch(err => {
            let child = tableBody.lastChild;
            while (child) {
                tableBody.removeChild(child);
                child = tableBody.lastChild;
            }
            const tr = document.createElement('tr');
            tr.classList.add('no-elements');
            tr.innerHTML = `<td colspan="6">${err}</td>`;
            tableBody.firstChild.remove();
            tableBody.append(tr)
        })
        .finally(() => loader.classList.add('close'));
}

function formCurrencyItem(item) {
    const tr = document.createElement('tr');
    let arrow = '';
    // &#8722; равно
    // &#9650; вверх
    // &#9660; вниз
    if (item?.OldValue) {
        if (+item.Value > +item.OldValue) {
            arrow = `<span class='arrowUp'>&#9650;</span>`;
        } else if (+item.Value === +item.OldValue) {
            arrow = `<span>&#8722;</span>`;
        } else {
            arrow = `<span class='arrowDown'>&#9660;</span>`;
        }
    }
    tr.innerHTML = `
    <td>${item.ValuteID}</td>
    <td>${item.NumCode}</td>
    <td>${item.CharCode}</td>
    <td>${item.Nominal}</td>
    <td>${item.Name}</td>
    <td class="currencyValue">${item.Value} ${arrow}</td>
    `;
    const tableBody = document.getElementById('tableBody');
    tableBody.append(tr)
}

async function formTableBody(items) {
    const tableBody = document.getElementById('tableBody');
    let child = tableBody.lastChild;
    while (child) {
        tableBody.removeChild(child);
        child = tableBody.lastChild;
    }
    items.filter((item) => item.visible).forEach(item => formCurrencyItem(item));
    if (items.length === 0) {
        const tr = document.createElement('tr');
        tr.classList.add('no-elements');
        tr.innerHTML = '<td colspan="6">Элементов не найдено</td>';
        if (tableBody.firstChild) {
            tableBody.firstChild.remove();
        }
        tableBody.append(tr)
    }
}

function formSelectOption(item, callback) {
    const div = document.createElement('div');
    div.className = 'select-option'
    const checkbox = document.createElement('input');
    checkbox.type = "checkbox";
    checkbox.name = "name";
    checkbox.value = "value";
    checkbox.id = `${item.ValuteID}`;
    checkbox.checked = item.visible;
    checkbox.addEventListener('change', callback)

    const label = document.createElement('label')
    label.htmlFor = `id${item.ValuteID}`;
    label.appendChild(document.createTextNode(item.Name));

    div.appendChild(checkbox);
    div.appendChild(label);
    return div;
}

function updateOptionsWatch() {
    currenciesToWatch = currenciesToWatch.map((currency) => {
        if (currency.ValuteID === this.id) {
            return {...currency, visible: this.checked}
        }
        return currency
    });
}

function updateOptionsRefresh() {
    currenciesToRefresh = currenciesToRefresh.map((currency) => {
        if (currency.ValuteID === this.id) {
            return {...currency, visible: this.checked}
        }
        return currency
    });
}

function formCurrenciesSelect() {
    const watchSelect = document.getElementById('watch-config');
    currenciesToWatch.map((item) => {
        const div = formSelectOption(item, updateOptionsWatch)
        watchSelect.append(div)
    })

    const refreshSelect = document.getElementById('refresh-config');
    currenciesToRefresh.map((item) => {
        const div = formSelectOption(item, updateOptionsRefresh)
        refreshSelect.append(div)
    })
}

function saveConf() {
    formTableBody(currenciesToWatch);
    localStorage.setItem('watch-config', JSON.stringify(currenciesToWatch));
    localStorage.setItem('refresh-config', JSON.stringify(currenciesToRefresh));
    giveOptionsToRefresh();
    refreshInterval = refreshIntervalInput.value;
    changeInterval();
}

async function giveOptionsToRefresh() {
    const data = currenciesToRefresh.filter((item) => !item.visible)
    return await fetch('./SetConfig.php', {
        method: 'POST',
        body: JSON.stringify(data)
    })
        .then(() => console.log('Опции успешно переданы'))
        .catch(() => console.log('В ходе передачи опций возникла ошибка'))
}

getCurrencies()
