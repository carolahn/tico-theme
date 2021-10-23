// import $ from "jquery";

// // prettier-ignore
// class Like {
//   constructor() {
//     this.events();
//   }

//   events() {
//     $(".like-box").on("click", this.ourClickDispatcher.bind(this));
//   }

//   // methods
//   ourClickDispatcher(e) {
//     let currentLikeBox = $(e.target).closest(".like-box");

//     // if (currentLikeBox.data("exists") == "yes") { // usando data() -> pega só o valor ao carregar a página
//     if (currentLikeBox.data("exists") == "yes") {   // usando attr() -> pega o valor atualizado
//       this.deleteLike(currentLikeBox);
//     } else {
//       this.createLike(currentLikeBox);
//     }
//   }

//   createLike(currentLikeBox) {
//     $.ajax({
//       url: ticoData.root_url + "/wp-json/tico/v1/manageLike",
//       beforeSend: (xhr) => {
//         xhr.setRequestHeader("X-WP-Nonce", ticoData.nonce);
//       },
//       type: "POST",
//       data: { designerId: currentLikeBox.data("designer") },
//       success: (response) => {
//           currentLikeBox.attr('data-exists', 'yes');
//           let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
//           likeCount++;
//           currentLikeBox.find(".like-count").html(likeCount);
//           currentLikeBox.attr('data-like', response);
//         console.log(response);
//       },
//       error: (response) => {
//         console.log(response);
//       },
//     });
//   }

//   deleteLike(currentLikeBox) {
//     $.ajax({
//       url: ticoData.root_url + "/wp-json/tico/v1/manageLike",
//       beforeSend: (xhr) => {
//         xhr.setRequestHeader("X-WP-Nonce", ticoData.nonce);
//       },
//       data: {'like': currentLikeBox.attr('data-like')},
//       type: "DELETE",
//       success: (response) => {
//         currentLikeBox.attr('data-exists', 'no');
//         let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
//         likeCount--;
//         currentLikeBox.find(".like-count").html(likeCount);
//         currentLikeBox.attr('data-like', '');
//         console.log(response);
//       },
//       error: (response) => {
//         console.log(response);
//       },
//     });
//   }
// }

// export default Like;

//////////////// Like sem JQuery do Brad //////////////////////////////////////////////////
import axios from "axios";
// prettier-ignore
class Like {
  constructor() {
    if (document.querySelector(".like-box")) {
      axios.defaults.headers.common["X-WP-Nonce"] = ticoData.nonce
      this.events()
    }
  }

  events() {
    document.querySelector(".like-box").addEventListener("click", e => this.ourClickDispatcher(e))
  }

  // methods
  ourClickDispatcher(e) {
    let currentLikeBox = e.target
    while (!currentLikeBox.classList.contains("like-box")) {
      currentLikeBox = currentLikeBox.parentElement
    }

    if (currentLikeBox.getAttribute("data-exists") == "yes") {
      this.deleteLike(currentLikeBox)
    } else {
      this.createLike(currentLikeBox)
    }
  }

  async createLike(currentLikeBox) {
    try {
      const response = await axios.post(ticoData.root_url + "/wp-json/tico/v1/manageLike", { "designerId": currentLikeBox.getAttribute("data-designer") })
      if (response.data != "Only logged in users can create a like.") {
        currentLikeBox.setAttribute("data-exists", "yes")
        var likeCount = parseInt(currentLikeBox.querySelector(".like-count").innerHTML, 10)
        likeCount++
        currentLikeBox.querySelector(".like-count").innerHTML = likeCount
        currentLikeBox.setAttribute("data-like", response.data)
      }
      console.log(response.data)
    } catch (e) {
      console.log("Sorry")
    }
  }

  async deleteLike(currentLikeBox) {
    try {
      const response = await axios({
        url: ticoData.root_url + "/wp-json/tico/v1/manageLike",
        method: 'delete',
        data: { "like": currentLikeBox.getAttribute("data-like") },
      })
      currentLikeBox.setAttribute("data-exists", "no")
      var likeCount = parseInt(currentLikeBox.querySelector(".like-count").innerHTML, 10)
      likeCount--
      currentLikeBox.querySelector(".like-count").innerHTML = likeCount
      currentLikeBox.setAttribute("data-like", "")
      console.log(response.data)
    } catch (e) {
      console.log(e)
    }
  }
}

export default Like;
