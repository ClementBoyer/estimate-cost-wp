(function() 
{
    tinymce.PluginManager.add('tel_button', function(editor, url) {
         // ajoute un bouton à tinyMCE
        editor.addButton('tel_button', 
        {
            text:'Téléphone',
            icon:false,
        onclick: function() 
        {     
            // On insère le contenu à l'endroit du curseur
            editor.insertContent
            (
                '<div class="form-group">'
                +'<label class="labeltext labeltel">Téléphone :</label>'
                +'<br>'
                +'<input id="telephone" name="telephone" class="form-control" type="tel" placeholder ="Votre téléphone" pattern="^0[1-9][0-9]{8}$" required></div>'
                +''

    
            );
        }
        });
});
})();



