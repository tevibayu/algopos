(function(w,d,s,g,js,fjs){
    g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(cb){this.q.push(cb)}};
    js=d.createElement(s);fjs=d.getElementsByTagName(s)[0];
    js.src='https://apis.google.com/js/platform.js';
    fjs.parentNode.insertBefore(js,fjs);js.onload=function(){g.load('analytics')};
}(window,document,'script'));

gapi.analytics.ready(function() {

    var CLIENT_ID = '1080411593535-jo59dn5lag5pln9c5p8nb83mk2dqh987.apps.googleusercontent.com';

    gapi.analytics.auth.authorize({
        container: 'auth-button',
        clientid: CLIENT_ID,
    });

    var viewSelector = new gapi.analytics.ViewSelector({
        container: 'view-selector'
    });

    viewSelector.execute();
    
    gapi.analytics.auth.on('success', function(response) {
        $('[id="analytic"]').show();
    });

    // users
    var users = new gapi.analytics.googleCharts.DataChart({
        query: {
            'metrics': 'ga:users',
            'dimensions': 'ga:date',
            'start-date': '7daysAgo',
            'end-date': 'today',
        },
        chart: {
            type: 'LINE',
            container: 'users',
            options: {
                width: '100%'
            }
        }
    });

    // country
    var country = new gapi.analytics.googleCharts.DataChart({
        query: {
            metrics: 'ga:sessions',
            dimensions: 'ga:country',
            'start-date': '4000daysAgo',
            'end-date': 'today',
            'max-results': 6,
            sort: '-ga:sessions'
        },
        chart: {
            container: 'country',
            type: 'PIE',
            options: {
                width: '100%',
                pieHole: 4/9
            }
        }
    });

    // browser
    var browser = new gapi.analytics.googleCharts.DataChart({
        query: {
            metrics: 'ga:pageviews',
            dimensions: 'ga:browser',
            'start-date': '4000daysAgo',
            'end-date': 'today',
            'max-results': 6,
            sort: '-ga:pageviews'
        },
        chart: {
            container: 'browser',
            type: 'PIE',
            options: {
                width: '100%',
                pieHole: 4/9
            }
        }
    });

    // pageview
    var pageview = new gapi.analytics.googleCharts.DataChart({
        query: {
            metrics: 'ga:pageviews',
            dimensions: 'ga:pagePath',
            'start-date': '4000daysAgo',
            'end-date': 'today',
            'max-results': 6,
            sort: '-ga:pageviews',
        },
        chart: {
            container: 'pageview',
            type: 'PIE',
            options: {
                width: '100%',
                pieHole: 4/9
            }
        }
    });

    viewSelector.on('change', function(ids) {
        users.set({query: {ids: ids}}).execute();
        country.set({query: {ids: ids}}).execute();
        browser.set({query: {ids: ids}}).execute();
        pageview.set({query: {ids: ids}}).execute();
    });
});