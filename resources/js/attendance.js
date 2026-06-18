window.clockInAttendance = async function () {
    try {
        const response = await fetch(
            '/asdasdasdaaaaaa',
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json',
                }
            }
        );

        const result = await response.json();

        if (result.success) {

            toastr.success(result.message);

            return;
        }

        toastr.error('Something went wrong.');

    } catch (error) {

        toastr.error('Unable to clock in.');

        console.error(error);
    }
};