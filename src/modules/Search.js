///////////// jQuery ///////////////////
import $ from "jquery";

// prettier-ignore
class Search {
  // 1. describe and create/initiate our object
  constructor() {
    // alert("Hello I am a search.");
    this.addSearchHTML(); // precisa ser o primeiro
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.events(); // para adicionar os events listeners assim que a pág é carregada
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  // 2. events (onClick, onFeelsHot)
  events() {
    this.openButton.on("click", this.openOverlay.bind(this)); // neces fazer o bind, pq o .on faz o this apontar para o botão
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  // 3. methods (function, actions)
  typingLogic() {
    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.html("");
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchField.val();
  }

  getResults() {
	$.getJSON(ticoData.root_url + "/wp-json/tico/v1/search?term=" + this.searchField.val(), (results) => {
		this.resultsDiv.html(`
			<div class="row">
				<div class="one-third">
					<h2 class="search-overlay__section-title">General Information</h2>
					${results.generalInfo.length ? '<ul class="link-list min-list">' : "<p>No general information matches that search.</p>"}
					${results.generalInfo.map((item) => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''}</li>`).join("")}
					${results.generalInfo.length ? "</ul>" : ""}
				</div>
				<div class="one-third">
					<h2 class="search-overlay__section-title">Stores</h2>
					${results.stores.length ? '<ul class="link-list min-list">' : `<p>No store matches that search. <a href="${ticoData.root_url}/stores">View all stores.</a></p>`}
					${results.stores.map((item) => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
					${results.stores.length ? "</ul>" : ""}
					<h2 class="search-overlay__section-title">Products</h2>
					${results.products.length ? '<ul class="link-list min-list">' : `<p>No product matches that search.  <a href="${ticoData.root_url}/products">View all products.</a></p>`}
					${results.products.map((item) => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
					${results.products.length ? "</ul>" : ""}
				</div>
				<div class="one-third">
					<h2 class="search-overlay__section-title">Designers</h2>
					${results.designers.length ? '<ul class="professor-cards">' : `<p>No designer matches that search.</p>`}
					${results.designers.map((item) => `
						<li class="professor-card__list-item">
							<a class="professor-card" href="${item.permalink}">
								<img class="professor-card__image" src="${item.image}">
								<span class="professor-card__name">${item.title}</span>
							</a>
						</li>
					`).join("")}
					${results.designers.length ? "</ul>" : ""}
					<h2 class="search-overlay__section-title">Patterns</h2>
					${results.patterns.length ? '' : `<p>No pattern matches that search.  <a href="${ticoData.root_url}/patterns">View all patterns.</a></p>`}
					${results.patterns.map((item) => `
						<div class="event-summary">
							<a class="event-summary__date t-center" href="${item.permalink}">
								<span class="event-summary__month">${item.month}</span>
								<span class="event-summary__day">${item.day}</span>
							</a>
							<div class="event-summary__content">
								<h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
								<p>
									${item.description}
									<a href="${item.permalink}" class="nu gray"> Go to pattern</a>
								</p>
							</div>
						</div>
					`).join("")}
				</div>
			</div>
		`);
		this.isSpinnerVisible = false;
	});
  }

  keyPressDispatcher(e) {
    // console.log(e.keyCode);
    if (
      e.keyCode == 83 &&
      this.isOverlayOpen == false &&
      !$("input, textarea").is(":focus")
    ) {
      // on press S
      console.log(e.keyCode);
      this.openOverlay();
    }
    if (e.keyCode == 27 && this.isOverlayOpen == true) {
      // on press Esc
      console.log(e.keyCode);
      this.closeOverlay();
    }
  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    setTimeout(() => this.searchField.trigger("focus"), 301);
    this.isOverlayOpen = true;
    return false; // para não enviar o user para a página /search
  }

  closeOverlay() {
    this.searchField.val("");
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
  }

  addSearchHTML() {
    $("body").append(`
		<div class="search-overlay">
			<div class="search-overlay__top">
				<div class="container">
					<i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
					<input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
					<i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
				</div>
			</div>

			<div class="container">
				<div id="search-overlay__results"></div>
			</div>
		</div>
	`);
  }
}

export default Search;

/////////// MEU JavaScript, não finalizado ////////////////////////////////////////////////////////////////////////
// class Search {
//   constructor() {
//     this.resultsDiv = document.querySelector("#search-overlay__results");
//     this.searchButton = document.querySelector(".js-search-trigger");
//     this.closeButton = document.querySelector(".search-overlay__close");
//     this.searchOverlay = document.querySelector(".search-overlay");
//     this.documentBody = document.querySelector("body");
//     this.searchField = document.querySelector("#search-term");
//     this.events();
//     this.isOverlayOpen = false;
//     this.isSpinnerVisible = false;
//     this.previousValue;
//     this.typingTimer;
//   }

//   typingLogic() {
//     if (this.searchField.value != this.previousValue) {
//       clearTimeout(this.typingTimer);
//       if (this.searchField.value) {
//         if (!this.isSpinnerVisible) {
//           this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>';
//           this.isSpinnerVisible = true;
//         }
//         this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
//       } else {
//         this.resultsDiv.innerHTML = "";
//         this.isSpinnerVisible = false;
//       }
//     }
//     this.previousValue = this.searchField.value;
//   }

//   getResults() {
//     this.resultsDiv.innerHTML = "Imagine real search results here!";
//     this.isSpinnerVisible = false;
//   }

//   keyPressDispatcher(e) {
//     if (
//       e.keyCode == 83 &&
//       this.isOverlayOpen == false &&
//       !~[].indexOf.call(
//         document.querySelectorAll("input, textarea"),
//         document.activeElement
//       )
//     ) {
//       // on press S
//       //   console.log(e.keyCode);
//       this.openOverlay();
//     }
//     if (e.keyCode == 27 && this.isOverlayOpen == true) {
//       // on press Esc
//       //   console.log(e.keyCode);
//       this.closeOverlay();
//     }
//   }

//   openOverlay() {
//     this.searchOverlay.classList.add("search-overlay--active");
//     this.documentBody.classList.add("body-no-scroll");
//     this.isOverlayOpen = true;
//   }

//   closeOverlay() {
//     this.searchOverlay.classList.remove("search-overlay--active");
//     this.documentBody.classList.remove("body-no-scroll");
//     this.isOverlayOpen = false;
//   }

//   events() {
//     this.searchButton.addEventListener("click", this.openOverlay.bind(this)); // neces fazer o bind, pq o .on faz o this apontar para o botão
//     this.closeButton.addEventListener("click", this.closeOverlay.bind(this));
//     document.addEventListener("keydown", this.keyPressDispatcher.bind(this));
//     this.searchField.addEventListener("keyup", this.typingLogic.bind(this));
//   }
// }

// export default Search;

//////////// JavaScript do Brad ///////////////////////////////////////////////////
// import axios from "axios";

// // prettier-ignore
// class Search {
//   // 1. describe and create/initiate our object
//   constructor() {
//     this.addSearchHTML()
//     this.resultsDiv = document.querySelector("#search-overlay__results")
//     this.openButton = document.querySelectorAll(".js-search-trigger")
//     this.closeButton = document.querySelector(".search-overlay__close")
//     this.searchOverlay = document.querySelector(".search-overlay")
//     this.searchField = document.querySelector("#search-term")
//     this.isOverlayOpen = false
//     this.isSpinnerVisible = false
//     this.previousValue
//     this.typingTimer
//     this.events()
//   }

//   // 2. events
//   events() {
//     this.openButton.forEach(el => {
//       el.addEventListener("click", e => {
//         e.preventDefault()
//         this.openOverlay()
//       })
//     })

//     this.closeButton.addEventListener("click", () => this.closeOverlay())
//     document.addEventListener("keydown", e => this.keyPressDispatcher(e))
//     this.searchField.addEventListener("keyup", () => this.typingLogic())
//   }

//   // 3. methods (function, action...)
//   typingLogic() {
//     if (this.searchField.value != this.previousValue) {
//       clearTimeout(this.typingTimer)

//       if (this.searchField.value) {
//         if (!this.isSpinnerVisible) {
//           this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'
//           this.isSpinnerVisible = true
//         }
//         this.typingTimer = setTimeout(this.getResults.bind(this), 750)
//       } else {
//         this.resultsDiv.innerHTML = ""
//         this.isSpinnerVisible = false
//       }
//     }

//     this.previousValue = this.searchField.value
//   }

//   async getResults() {
//     try {
//       const response = await axios.get(ticoData.root_url + "/wp-json/tico/v1/search?term=" + this.searchField.value)
//       const results = response.data
//       this.resultsDiv.innerHTML = `
//         <div class="row">
//           <div class="one-third">
//             <h2 class="search-overlay__section-title">General Information</h2>
//             ${results.generalInfo.length ? '<ul class="link-list min-list">' : "<p>No general information matches that search.</p>"}
//               ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == "post" ? `by ${item.authorName}` : ""}</li>`).join("")}
//             ${results.generalInfo.length ? "</ul>" : ""}
//           </div>
//           <div class="one-third">
//             <h2 class="search-overlay__section-title">Products</h2>
//             ${results.products.length ? '<ul class="link-list min-list">' : `<p>No products match that search. <a href="${ticoData.root_url}/products">View all products</a></p>`}
//               ${results.products.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
//             ${results.products.length ? "</ul>" : ""}

//             <h2 class="search-overlay__section-title">Designers</h2>
//             ${results.designers.length ? '<ul class="professor-cards">' : `<p>No designers match that search.</p>`}
//               ${results.designers
//           .map(
//             item => `
//                 <li class="professor-card__list-item">
//                   <a class="professor-card" href="${item.permalink}">
//                     <img class="professor-card__image" src="${item.image}">
//                     <span class="professor-card__name">${item.title}</span>
//                   </a>
//                 </li>
//               `
//           )
//           .join("")}
//             ${results.designers.length ? "</ul>" : ""}

//           </div>
//           <div class="one-third">
//             <h2 class="search-overlay__section-title">Stores</h2>
//             ${results.stores.length ? '<ul class="link-list min-list">' : `<p>No stores match that search. <a href="${ticoData.root_url}/stores">View all stores</a></p>`}
//               ${results.stores.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
//             ${results.stores.length ? "</ul>" : ""}

//             <h2 class="search-overlay__section-title">Patterns</h2>
//             ${results.patterns.length ? "" : `<p>No patterns match that search. <a href="${ticoData.root_url}/patterns">View all patterns</a></p>`}
//               ${results.patterns
//           .map(
//             item => `
//                 <div class="event-summary">
//                   <a class="event-summary__date t-center" href="${item.permalink}">
//                     <span class="event-summary__month">${item.month}</span>
//                     <span class="event-summary__day">${item.day}</span>
//                   </a>
//                   <div class="event-summary__content">
//                     <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
//                     <p>${item.description} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
//                   </div>
//                 </div>
//               `
//           )
//           .join("")}

//           </div>
//         </div>
//       `
//       this.isSpinnerVisible = false
//     } catch (e) {
//       console.log(e)
//     }
//   }

//   keyPressDispatcher(e) {
//     if (e.keyCode == 83 && !this.isOverlayOpen && document.activeElement.tagName != "INPUT" && document.activeElement.tagName != "TEXTAREA") {
//       this.openOverlay()
//     }

//     if (e.keyCode == 27 && this.isOverlayOpen) {
//       this.closeOverlay()
//     }
//   }

//   openOverlay() {
//     this.searchOverlay.classList.add("search-overlay--active")
//     document.body.classList.add("body-no-scroll")
//     this.searchField.value = ""
//     setTimeout(() => this.searchField.focus(), 301)
//     console.log("our open method just ran!")
//     this.isOverlayOpen = true
//     return false
//   }

//   closeOverlay() {
//     this.searchOverlay.classList.remove("search-overlay--active")
//     document.body.classList.remove("body-no-scroll")
//     console.log("our close method just ran!")
//     this.isOverlayOpen = false
//   }

//   addSearchHTML() {
//     document.body.insertAdjacentHTML(
//       "beforeend",
//       `
//       <div class="search-overlay">
//         <div class="search-overlay__top">
//           <div class="container">
//             <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
//             <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
//             <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
//           </div>
//         </div>

//         <div class="container">
//           <div id="search-overlay__results"></div>
//         </div>

//       </div>
//     `
//     )
//   }
// }

// export default Search;
