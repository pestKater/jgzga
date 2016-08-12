$('form').submit( function(ev) {
    var textarea = $('#formcustom textarea');
    var text = textarea.val();

    text = text.replace(/ä/g,"&auml;");
    text = text.replace(/ö/g, '&ouml;');
    text = text.replace(/ü/g, '&uuml;');
    text = text.replace(/Ä/g, '&Auml;');
    text = text.replace(/Ö/g, '&Ouml;');
    text = text.replace(/Ü/g, '&Uuml;');
    text = text.replace(/ß/g, '&szlig;');

    textarea.val(text);

    var title = $('#formcustom #title');
    var text = title.val();

    text = text.replace(/ä/g,"&auml;");
    text = text.replace(/ö/g, '&ouml;');
    text = text.replace(/ü/g, '&uuml;');
    text = text.replace(/Ä/g, '&Auml;');
    text = text.replace(/Ö/g, '&Ouml;');
    text = text.replace(/Ü/g, '&Uuml;');
    text = text.replace(/ß/g, '&szlig;');

    title.val(text);
});