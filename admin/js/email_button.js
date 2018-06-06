(function() 
{
    tinymce.PluginManager.add('email_button', function(editor, url) {
         // ajoute un bouton à tinyMCE
        editor.addButton('email_button', 
        {
            text:'Mail',
            icon:false,
        onclick: function() 
        {     
            // On insère le contenu à l'endroit du curseur
            editor.insertContent
            (
                '<div class="form-group">'
                +'<label class="labeltext labelmail">Email :</label>'
                +'<br>'
                +'<input id="email" name="email" class="form-control" type="email" placeholder ="Votre email" pattern="[a-z0-9.-]+@[a-z0-9]+\.[a-z]{2,3}$" required></div>'

    
            );
        }
        });
});
})();



