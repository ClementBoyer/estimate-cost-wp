(function() {
    tinymce.PluginManager.add('custom_button', function(editor, url) {
      // ajoute un bouton à tinyMCE
      editor.addButton('custom_button', {
  
      // texte par défaut du bouton
      // on peut mettre une icône, 
      // mais il faudra que vous trouviez ça tout seul ;)
      text: 'Texte',
      icon: false,
      onclick: function() {
        // On ouvre une fenêtre modale
        // qui permet à l'utilisateur d'entrer ses données
        // de manière interactive
        editor.windowManager.open( {
          // titre du popup
          title: 'Entrez votre texte',
          body: [
          // on peut mettre autant de champs que l'on veut
          // textbox est un champ de type input
          // name est l'attribut, 
          // vous vous en servirez donc pour récupérer le contenu
          {
            type: 'textbox',
            name: 'title',
            label: 'Title'
          },
  
          // un deuxième champ
          {
            type: 'textbox',
            name: 'information',
            label: 'Information (placeholder)'
          },
        
          {
            type: 'checkbox',
            name: 'obligatoire',
            label: 'Champ obligatoire',
            checked: true

          }],
          // l'action a effectuer lorsque l'utilisateur valide la modale
          onsubmit: function(e) {
           
            // On insère le contenu à l'endroit du curseur
            editor.insertContent(
              '<label class="Label'+e.data.title+'">' 
            + e.data.title +'</label>'
            +'<br>'
            +'<input name ='+'"'+e.data.title+'"'+ ' class="form-control" type="text" placeholder ="'+e.data.information+'" '
            +required()
            +'>'
            );

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