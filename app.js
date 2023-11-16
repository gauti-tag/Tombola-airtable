document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("pass-stam23");
    const ouiRadio = document.getElementById("oui");
    const nonRadio = document.getElementById("non");
    const fieldset = document.getElementById("fieldsetLearning");
    let errorMsg = "";
    let isValid = true;
    let formData;

    ouiRadio.addEventListener("change", function () {
        if (this.checked) {
            fieldset.disabled = false;
        }
    });
    nonRadio.addEventListener("change", function () {
        if (this.checked) {
            fieldset.disabled = true;
        }
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        errorMsg = ""; // Réinitialiser le message d'erreur
        isValid = true; // Réinitialiser la validité

        const name = document.getElementById("name");
        if (name.value.trim() === "") {
            isValid = false;
            errorMsg += "Le nom est requis.\n";
        }

        const genre = document.querySelector('input[name="genre"]:checked');
        if (!genre) {
            isValid = false;
            errorMsg += "Veuillez sélectionner une option dans Sexe.\n";
        }

        const email = document.getElementById("email");
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            isValid = false;
            errorMsg += "Veuillez entrer une adresse email valide.\n";
        }

        const phone = document.getElementById("phone");
        const phoneRegex = /^(\+225|00225)?[ -]?(\d{2}[ -]?){4}\d{2}$/;
        if (!phoneRegex.test(phone.value.trim())) {
            isValid = false;
            errorMsg += "Veuillez entrer un numéro de téléphone valide.\n";
        }

        // Validation pour les Masterclass
        const ouiRadio = document.getElementById("oui");
        const masterclassCheckboxes = document.querySelectorAll(
            '#fieldsetLearning input[type="checkbox"]'
        );
        const isAnyMasterclassChecked = Array.from(masterclassCheckboxes).some(
            (checkbox) => checkbox.checked
        );

        if (ouiRadio.checked && !isAnyMasterclassChecked) {
            isValid = false;
            errorMsg += "Veuillez sélectionner au moins une Masterclass.\n";
        }

        // Get all checkboxes
        var checkboxes = document.getElementsByName('learning');
        // Array to store checked values
        var checkedValues = [];
        // Loop through checkboxes and add checked values to the array
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checkedValues.push(checkboxes[i].value);
            }
        }
        // Assign to an entry form 
        const master_class = document.getElementById("get_learning");
        master_class.value = checkedValues.join('; ');

        // Get current timestamp in milliseconds for using as ticket number
        var timestamp = new Date().getTime();

        // Extract the last 5 digits and  assign to an entry form
        var lastFiveDigits = String(timestamp).slice(-5);
        const ticket = document.getElementById("ticket_number");
        ticket.value = lastFiveDigits;

        // If true submit the forme
        if (isValid) {
            formData = new FormData(form);
            //const data = Object.fromEntries(formData);
            //for (let pair of formData.entries()) {
            //  console.log(pair[0] + ": " + pair[1]);
            0// }
            showModal(
                `Vous vous dirigez vers votre Pass et ID de tombola, (une copie vous sera transmise par email)`
            );
        } else {
            alert(errorMsg);
        }
    });

    function showModal(message) {
        const modal = document.querySelector(".modal");
        const modalMessage = document.getElementById("modal-message");
        const modalOkBtn = document.getElementById("modal-ok-btn");

        modalMessage.textContent = message;
        modal.style.display = "block";

        modalOkBtn.addEventListener("click", async function () {
            modalOkBtn.disabled = true; // Désactive le bouton OK pour éviter des clics multiples
            const modalCloseBtn = document.querySelector(".modal-close-btn");
            modalCloseBtn.addEventListener("click", function () {
                const modal = document.querySelector(".modal");
                modal.style.display = "none";
            });
            // Show loading overlay
            document.getElementById('loading-overlay').style.display = 'flex';

            try {

                fetch("server/script.php", {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((json) => {
                        form.reset(); // Réinitialise le formulaire
                        modal.style.display = "none"; // Ferme la modale
                        console.log(json)
                        const _file = window.open(json.url, "_blank");
                        _file.focus();

                    })
                    .catch((error) => {
                        console.error("Erreur lors de la soumission du formulaire:", error);
                        alert("Une erreur est survenue lors de la soumission du formulaire.");
                    });

                // Simulate processing the data (you can replace this with your actual logic)
                await new Promise(resolve => setTimeout(resolve, 3000));

            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                // Hide loading overlay when the fetch request is complete (whether successful or with an error)
                document.getElementById('loading-overlay').style.display = 'none';
            }

        });
    }


});
