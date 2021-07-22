//recup du click supp , recup du token , envoi de la requete à l'url pour supp l'image , quand on a la reponse si tt se passe bien on supprime img et lien 

window.onload = () => {
    let links = document.querySelectorAll("[data-delete]")
    //boucle sur le click
    for(link of links){
        //ecoute du click
        link.addEventListener("click", function(e){
            // on empeche la navigation
            e.preventDefault()

            //demande confirmation
            if(confirm('Voulez-vous supprimer cette image ?')){
                // requete ajax vers le href du lien avec la methode delete
                fetch(this.getAttribute("href"),{
                    method:"DELETE",
                    headers: {
                        "X-requested-with": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },          
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    //recuperation de la rep en json
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)

                }).catch(e => alert(e))
            }

        })
    }
}

