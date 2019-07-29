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
);