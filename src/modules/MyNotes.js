import $ from "jquery";

// prettier-ignore
class MyNotes {
  constructor() {
    this.events();
  }

  events() {
    $(".delete-note").on("click", this.deleteNote);
    $(".edit-note").on("click", this.editNote.bind(this));
    $(".update-note").on("click", this.updateNote.bind(this));
  }

  // Methods
  editNote(e) {
    let thisNote = $(e.target).parents("li");
    if (thisNote.data("state") == "editable") {
        this.makeNoteReadOnly(thisNote);
    } else {
        this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
    thisNote.find(".update-note").addClass("update-note--visible");
    thisNote.data("state", "editable");
  }

  makeNoteReadOnly(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
    thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
    thisNote.find(".update-note").removeClass("update-note--visible");
    thisNote.data("state", "cancel");
  }

  deleteNote(e) {
    let thisNote = $(e.target).parents("li"); // e.target é o button
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", ticoData.nonce);
      },
      url: ticoData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"), // a função jQuery .data() não precisa receber o atributo completo data-id
      type: "DELETE",
      success: (response) => {
        thisNote.slideUp();
        console.log("Delete Success");
        console.log(response);
      },
      error: (response) => {
        console.log("Error");
        console.log(response);
      },
    });
  }

  updateNote(e) {
    let thisNote = $(e.target).parents("li"); 
    let ourUpdatedPost = {
        'title': thisNote.find(".note-title-field").val(),
        'content': thisNote.find(".note-body-field").val()
    };

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", ticoData.nonce);
      },
      url: ticoData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"), 
      type: "POST",
      data: ourUpdatedPost,
      success: (response) => {
          this.makeNoteReadOnly(thisNote);
        console.log("Post Success");
        console.log(response);
      },
      error: (response) => {
        console.log("Error");
        console.log(response);
      },
    });
  }
}

export default MyNotes;
