function onClickLike(event) {
  event.preventDefault();

  const url = this.href;
  const spanCount = this.querySelector("span.js-likes");
  const icone = this.querySelector("i.fa-thumbs-up");

  axios
    .get(url)
    .then(function (response) {
      spanCount.textContent = response.data.likes;
      if (icone.classList.contains("fas")) {
        icone.classList.replace("fas", "far");
      } else {
        icone.classList.replace("far", "fas");
      }

      if (
        response.data.message ===
        "Le dislkie a été supprimé et le like a été ajouté"
      ) {
        const spanCountDis = document.querySelector("span.js-dislikes");
        const iconeDis = document.querySelector("i.fa-thumbs-down");

        spanCountDis.textContent = response.data.dislikes;

        if (iconeDis.classList.contains("fas")) {
          iconeDis.classList.replace("fas", "far");
        } else {
          iconeDis.classList.replace("far", "fas");
        }
      }
    })
    .catch(function (error) {
      if (error.response.status === 403) {
        window.alert("Vous devez obligatoirement vous connecter");
      } else {
        window.alert("Une erreur non identifiée s'est produite.");
      }
    });
}

function onCLickDislike(event) {
  event.preventDefault();

  const urlDislike = this.href;
  const spanCountDislike = this.querySelector("span.js-dislikes");
  const iconeDislike = this.querySelector("i.fa-thumbs-down");

  axios.get(urlDislike).then(function (response) {
    spanCountDislike.textContent = response.data.dislikes;

    if (iconeDislike.classList.contains("fas")) {
      iconeDislike.classList.replace("fas", "far");
    } else {
      iconeDislike.classList.replace("far", "fas");
    }

    if (
      response.data.message ==
      "Le like a été supprimé et le dislike a été ajouté"
    ) {
      const spanCountLike = document.querySelector("span.js-likes");
      const iconeLike = document.querySelector("i.fa-thumbs-up");

      spanCountLike.textContent = response.data.likes;

      if (iconeLike.classList.contains("fas")) {
        iconeLike.classList.replace("fas", "far");
      } else {
        iconeLike.classList.replace("far", "fas");
      }
    }
  });
}

document.querySelector("a.js-likes").addEventListener("click", onClickLike);

document
  .querySelector("a.js-dislikes")
  .addEventListener("click", onCLickDislike);
