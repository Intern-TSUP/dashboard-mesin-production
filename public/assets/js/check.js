function devMenu (id)
{
    const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");
    $.ajax({
      type: "POST",
      url: "/dev/checkOtp",
      data: { _token: csrfToken },
      success: function (response) {
        window.location.href = '/dev';
      },
      error: function (xhr) {
        // Show alert if login fails
        if (xhr.status === 401) {
          Swal.fire({
            icon: "error",
            title: "Access expired",
            text: xhr.responseJSON.error,
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "Access Needed!",
                text: "Kirim OTP kepada Developer?",
                confirmButtonText: "Request OTP",
                showCancelButton: true,
                allowOutsideClick: false, // Tidak bisa ditutup dengan klik luar
                allowEscapeKey: false,
                showCancelButton: true,
                confirmButtonText: "Confirm",
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                  const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");

                  // Return a promise that resolves or rejects based on the AJAX response
                  return $.ajax({
                    type: "POST",
                    url: "/dev/otp",
                    data: {
                      _token: csrfToken, // Include the CSRF token
                    },
                  })
                    .then((data) => {
                      // Assuming the server returns a success response when the password is correct
                      if (data.success) {
                        return data; // Resolve the promise to indicate success
                      } else {
                        Swal.showValidationMessage("Something went wrong!"); // Show validation message
                        throw new Error("Something went wrong!"); // Reject the promise
                      }
                    })
                    .catch((xhr) => {
                      // Handle error
                      Swal.showValidationMessage(
                        `Request failed: ${xhr.responseText}`
                      );
                      Swal.close();
                      Swal.fire({
                        icon: "error",
                        title: "Error Sending OTP",
                        html: `There was an error when create OTP. <br> Please try again. <br> ${xhr.responseText}`,
                      });
                      throw new Error(xhr.responseText); // Reject the promise to stop loading
                    });
                },
                allowOutsideClick: () => !Swal.isLoading(),
              }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire({
                    title:
                      '<i class="ki-duotone ki-lock-2 fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                    text: "An OTP has been sent to developer email. Contact your Developer to get the OTP.",
                    input: "number",
                    inputPlaceholder: "input OTP here",
                    inputAttributes: {
                      autocapitalize: "off",
                      autocorrect: "off",
                    },
                    allowOutsideClick: false, // Tidak bisa ditutup dengan klik luar
                    allowEscapeKey: false,
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    showLoaderOnConfirm: true,
                    preConfirm: async (otp) => {
                      const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                      // Return a promise that resolves or rejects based on the AJAX response
                      return $.ajax({
                        type: "POST",
                        url: "/dev/auth",
                        data: {
                          otp: otp,
                          _token: csrfToken, // Include the CSRF token
                        },
                      })
                        .then((data) => {
                          // Assuming the server returns a success response when the password is correct
                          if (data.success) {
                            return data; // Resolve the promise to indicate success
                          } else {
                            Swal.showValidationMessage("Something went Wrong!"); // Show validation message
                            throw new Error("Something went Wrong!"); // Reject the promise
                          }
                        })
                        .catch((xhr) => {
                          // Handle error
                          Swal.showValidationMessage(
                            `Request failed: ${xhr.responseText}`
                          );
                          Swal.close();
                          Swal.fire({
                            icon: "error",
                            title: "Error Verified OTP",
                            html: `There was an error when otp is verified. <br> Please try again.`,
                          });
                          throw new Error(xhr.responseText); // Reject the promise to stop loading
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading(),
                  }).then((result) => {
                    if (result.isConfirmed) {
                      $.ajax({
                        type: "GET",
                        url: result.value.url,
                        data: { is_pass: result.value.is_pass },
                        beforeSend: function () {
                          Swal.fire({
                            title: "processing...",
                            text: "Please wait while we process your request.",
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                              Swal.showLoading();
                            },
                          });
                        },
                        success: function (response) {
                          // Added parentheses and a parameter
                          let timerInterval;
                          Swal.fire({
                            icon: "success",
                            title: "Successfull",
                            text: response.message + response.by,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willOpen: () => {
                              Swal.showLoading();
                            },
                            willClose: () => {
                              clearInterval(timerInterval);
                              window.location.href = 'dev';
                            },
                          });
                        },
                        error: function () {
                          // Added parentheses and parameters
                          Swal.fire({
                            icon: "error",
                            title: "Something went wrong!", // Fixed typo "when" to "went"
                          });
                        },
                      });
                    }
                  });
                }else{
                  window.location.href = '/dashboard';
                }
              });
            }
          });
        }
      },
    });
}