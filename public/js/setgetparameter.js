function setGetParameter(paramName, paramValue, redirect)
{
    redirect = typeof redirect !== 'undefined' ? redirect : true;

    var url = window.location.href;
    if (url.indexOf(paramName + "=") >= 0)
    {
        var prefix = url.substring(0, url.indexOf(paramName));
        var suffix = url.substring(url.indexOf(paramName)).substring(url.indexOf("=") + 1);
        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
        url = prefix + paramName + "=" + paramValue + suffix;
    }
    else
    {
    if (url.indexOf("?") < 0)
        url += "?" + paramName + "=" + paramValue;
    else
        url += "&" + paramName + "=" + paramValue;
    }
    console.log(url);
    if (redirect)
        window.location = url;
    else
        return url;

}

var addSearchQuery = function(){
        var query = document.getElementById('search_query').value;
        if (query != null){
            var filter_form = $('#filter_form');
            if (filter_form.length){
                $('#search').val(query);
                filter_form.submit();
                return false;
            }
        }
}