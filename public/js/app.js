// Set up a Bloodhound to include prefetch, for all countries we already know about, and remote, to get
// countries from the API if we don't have them yet.
let countries = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: '/prefetch.php',
    remote: {
        url: '/remote.php?query=%QUERY',
        wildcard: '%QUERY'
    },
    dupDetector: function (remoteMatch, localMatch) {
        return remoteMatch.name === localMatch.name;
    }
});

$('.typeahead').typeahead(
    {
        hint: true,
        highlight: true,
        minLength: 2
    },
    {
        name: 'countries',
        display: 'name',
        source: countries,
    }
).on('typeahead:selected typeahead:autocompleted', function (e, datum) {
    console.log(datum);
    $('#country-name').html(datum.name);
    $('#region').html(datum.region);
    $('#calling-code').html(datum.numericCode);
    $('#capital').html(datum.capital);
    $('#timezones').html(datum.timezones.join('<br/>'));
    $('#flag').attr('src', datum.flag);

    // We need a bit of extra processing because the array items in the currency array are objects.
    let currencies = [];

    for (let i = 0, length = datum.currencies.length; i < length; i++) {
        currencies.push(datum.currencies[i].name);
    }

    $('#currencies').html(currencies.join('<br/>'));

    $('#result').fadeIn();
});
