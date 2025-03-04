document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const subDistrictSelect = document.getElementById('sub_district_id');
    const zipcodeInput = document.getElementById('zipcode');

    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        fetch(`get_districts.php?province_id=${provinceId}`)
            .then(response => response.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">เลือกอำเภอ</option>';
                data.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district.id}">${district.name_th}</option>`;
                });
                subDistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>'; // Clear sub-districts
                zipcodeInput.value = ''; // Clear zipcode
            });
    });

    districtSelect.addEventListener('change', function () {
        const districtId = this.value;
        if (districtId) {
            fetch(`get_sub_districts.php?district_id=${districtId}`)
                .then(response => response.json())
                .then(data => {
                    subDistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>';
                    zipcodeInput.value = '';
                    data.forEach(subDistrict => {
                        subDistrictSelect.innerHTML += `<option value="${subDistrict.id}" data-zipcode="${subDistrict.zip_code}">${subDistrict.name_th}</option>`;
                    });
                });
        } else {
            subDistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>'; // Clear sub-districts
            zipcodeInput.value = ''; // Clear zipcode
        }
    });

    subDistrictSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const zipcode = selectedOption.getAttribute('data-zipcode');
        zipcodeInput.value = zipcode;
    });
});
