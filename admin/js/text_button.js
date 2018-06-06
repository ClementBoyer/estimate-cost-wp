(function() {
    tinymce.PluginManager.add('text_button', function(editor, url) {
      // ajoute un bouton à tinyMCE
      editor.addButton('text_button', {
  
      // Titre boutton
      text: 'Texte',
      icon: false,
      onclick: function() {
        // Ouverture fenêtre 
        editor.windowManager.open( {
          // titre du popup
          title: 'Entrez votre texte',
          body: [
          // champ label
          {
            type: 'textbox',
            name: 'title',
            label: 'Title'
          },
  
          // champ placeholder
          {
            type: 'textbox',
            name: 'information',
            label: 'Information (placeholder)'
          },
          
          // champ required
          {
            type: 'checkbox',
            name: 'obligatoire',
            label: 'Champ obligatoire',
            checked: true

          },],
          onsubmit: function(e) {
           
            // On insère le contenu à l'endroit du curseur
            editor.insertContent(
            '<div class="form-group">'
            +'<label class="labeltext label'+e.data.title+'">' 
            + e.data.title +'</label>'
            +'<br>'
            +'<input id='+'"'+e.data.title+'"'+ 'name='+'"'+e.data.title+'"'+ ' class="form-control" type="text" placeholder ="'+e.data.information+'" '
            +required()
            +'></div>'
            );

            // fonction pour insérer required dans input si case cocher
            function required()
            {
              if ( e.data.obligatoire == true)
              {
                return 'required';
              }
              else
              {
                return '';
              }
            }
            }
          });
        }
      });
    });
  })();



