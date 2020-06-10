
const $ = require('jquery');

// classe gérant la grille de questions
class QRGrid {
    constructor(root) {
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of QRGrid.';
            root = $(root);
        }
        this.root = root;
        this.id = root.prop('id');

        this.QRlist = [];


	}

	//remplissage de la grille
    remplissageQRgrid() {


		// Texte des questions réponses pour le remplissage
/*		Qlist = ["Que Signifie le décompte à gauche de l'écran ?", "J'ai résolu une énigme, comment passer à l'étape suivante ?", "Que faire en cas de problème ?"];
		Rlist = [ "Au fur et à mesure de votre progression, vous gagnez des points en fonction du temps \
		passé à résoudre les énigmes selon le barème suivant : \n\n\
		moins de 8 minutes : 20 points \n\
		entre 8 et 10 minutes : 10 points \n\
		plus de 10 minutes : -10 points \n\n\
		L'icone à côté de votre nombre de points vous indique votre classement\
		par rapport aux autres équipes",
		"Vous avez terminé une étape ? Bravo ! Il ne vous reste plus qu'a la valider, grâce au game master de l'énigme :\
		demandez lui le code de validation, et entrez le dans le champ correspondant pour passer à l'étape suivante.",
		"Contactez le game master le plus proche. Les game masters sont reconnaissables grâce à leurs badges."];*/

		let faq = document.getElementById('FaQ');
		let conteneur = document.createElement('div');
		faq.appendChild(conteneur);
		conteneur.setAttribute("id","conteneur");
		conteneur.setAttribute("class","jumbotron")
		$('#conteneur').append(
			'<div id="accordion">' +
			"  <div class='card'>" +
			"    <div class='card-header' id='headingOne' style='background-color: #e3e9ec !important'>" +
			"      <h5 class='mb-0'>" +
			"        <button class='btn btn-link' data-toggle='collapse' data-target='#collapseOne' aria-expanded='false' aria-controls='collapseOne' style='color: #4c5356'>" +
			"          I - Que signifie le décompte à gauche de l'écran ?" +
			"        </button>" +
			"      </h5>" +
			"    </div>" +
			"    <div id='collapseOne' class='collapse' aria-labelledby='headingOne' data-parent='#accordion'>" +
			"      <div class='card-body'>" +
			"Au fur et à mesure de votre progression, vous gagnez des points en fonction du temps passé à résoudre les énigmes selon le barème suivant :" +
			"<br>" +
			"moins de 8 minutes : 20 points<br>" +
			"entre 8 et 10 minutes : 10 points<br>" +
			"plus de 10 minutes : -10 points<br>" +
			"<br>" +
			"L'icône à côté de votre nombre de points vous indique votre classement par rapport aux autres équipes.<br>"+
			"      </div>" +
			"    </div>" +
			"  </div>" +
			" <div class='card'>" +
			"    <div class='card-header' id='headingTwo' style='background-color: #e3e9ec !important'>" +
			"      <h5 class='mb-0'>\n" +
			"        <button class='btn btn-link collapsed' data-toggle='collapse' data-target='#collapseTwo' aria-expanded='false' aria-controls='collapseTwo' style='color: #4c5356'>" +
			"          II - J’ai résolu une énigme, comment passer à l’étape suivante ?" +
			"        </button>" +
			"      </h5>" +
			"    </div>" +
			"    <div id='collapseTwo' class='collapse' aria-labelledby='headingTwo' data-parent='#accordion'>\n" +
			"      <div class='card-body'>\n" +
"Vous avez terminé une étape ? Bravo ! Il ne vous reste plus qu’à la valider, grâce au game master de l’énigme : demandez lui le code de validation, et entrez le dans le champ correspondant pour passer à l’étape suivante."+
			"      </div>" +
			"    </div>" +
			"  </div>" +
			"  <div class='card'>" +
			"    <div class='card-header' id='headingThree' style='background-color: #e3e9ec !important'>\n" +
			"      <h5 class='mb-0'>" +
			"        <button class='btn btn-link collapsed' data-toggle='collapse' data-target='#collapseThree' aria-expanded='false' aria-controls='collapseThree' style='color: #4c5356'>" +
			"          III - Que faire en cas de problème ?\n" +
			"        </button>" +
			"      </h5>" +
			"    </div>" +
			"    <div id='collapseThree' class='collapse' aria-labelledby='headingThree' data-parent='#accordion'>" +
			"      <div class='card-body'>" +
"Contactez le game master le plus proche. Les game masters sont reconnaissables grâce à leurs badges."+
			"      </div>\n" +
			"    </div>\n" +
			"  </div>"+
			"</div>"
	);
/*        for (let i = 0; i<Qlist.length;i++){


			let QR = document.createElement('div');
			let Q = document.createElement('h4');
			Q.textContent = Qlist[i];

			let R = document.createElement('p');
			R.textContent= Rlist[i];
			R.style.display = 'none';

			Q.addEventListener('click', () => {
				if (R.style.display === "none") {
					R.style.display = "block";
				} else {
					R.style.display = "none";
				}
			});

			QR.appendChild(Q);
			QR.appendChild(R);

			conteneur.appendChild(QR);
		}*/

    }


}

exports.QRGrid = QRGrid;
