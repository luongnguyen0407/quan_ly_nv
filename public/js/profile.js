$(document).ready(function () {
  const modal = document.querySelector("#modal-3");
  const modalPass = document.querySelector("#modal-4");
  const image = document.getElementById("img_crop");
  const formChangePass = document.querySelector(".form_modal_pass");
  let file;
  let reader;
  let cropper;

  $(".update_pass").click(function () {
    modalPass.classList.add("is-open");
    modalPass.setAttribute("aria-hidden", "false");
  });

  $("#input_avatar").change(function () {
    let fileImg = this.files;
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    var done = function (url) {
      image.src = url;
    };

    if (fileImg && fileImg.length > 0) {
      file = fileImg[0];
      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
    cropper = new Cropper(image, {
      aspectRatio: 0,
      viewMode: 2,
      preview: ".img_preview",
    });
  });

  $("#modal-3 .modal__close").click(function () {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    cropper.destroy();
    cropper = null;
  });

  $("#modal-4 .modal__close").click(function () {
    modalPass.classList.remove("is-open");
    modalPass.setAttribute("aria-hidden", "true");
  });

  $(".btn_crop").click(function () {
    canvas = cropper.getCroppedCanvas({
      width: 500,
      height: 700,
    });

    canvas.toBlob(function (blob) {
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function () {
        var base64data = reader.result;
        $.ajax({
          url: "./Ajax/updateAvatarBase64",
          method: "POST",
          data: { image: base64data },
          success: function () {
            location.reload();
          },
          error: function () {
            showToast("L???i");
          },
        });
      };
    });
  });

  //change pass
  formChangePass.addEventListener("submit", (e) => {
    const passOld = document.querySelector(".passOld").value;
    const passNew = document.querySelector(".passNew").value;
    const passNewRef = document.querySelector(".passNewRef").value;
    e.preventDefault();
    if (!passOld || !passNew || !passNewRef)
      return showToast("B???n c???n nh??p ????? tr?????ng");
    if (passNew !== passNewRef)
      return showToast("M???t kh???u nh???p l???i kh??ng gi???ng");
    $.ajax({
      url: "./Ajax/updatePass",
      method: "POST",
      data: {
        passOld,
        passNew,
        passNewRef,
      },
      success: function (res) {
        showToast("?????i m???t kh???u th??nh c??ng");
        $(".form_modal_pass input").val("");
        modalPass.classList.remove("is-open");
        modalPass.setAttribute("aria-hidden", "true");
      },
      error: function () {
        showToast("Sai m???t kh???u c??");
      },
    });
  });

  function showToast(mess = "This is a toast") {
    Toastify({
      text: mess,
      duration: 2000,
      newWindow: true,
      close: true,
      gravity: "top", // `top` or `bottom`
      position: "right", // `left`, `center` or `right`
      stopOnFocus: true, // Prevents dismissing of toast on hover
      style: {
        background: "#9CFF2E",
      },
      onClick: function () {}, // Callback after click
    }).showToast();
  }
});
