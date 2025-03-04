document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const districtSelect = document.getElementById('district_id');
    const subDistrictSelect = document.getElementById('sub_district_id');
    const zipcodeInput = document.getElementById('zipcode');

    districtSelect.innerHTML = '<option value="">Select District</option>';
    subDistrictSelect.innerHTML = '<option value="">Select Sub-District</option>';
    zipcodeInput.value = '';

    fetch('get_districts.php?province_id=' + provinceId)
        .then(response => response.json())
        .then(data => {
            data.forEach(district => {
                districtSelect.innerHTML += `<option value="${district.id}">${district.name_th}</option>`;
            });
        });
});

document.getElementById('district_id').addEventListener('change', function() {
    const districtId = this.value;
    const subDistrictSelect = document.getElementById('sub_district_id');
    const zipcodeInput = document.getElementById('zipcode');

    subDistrictSelect.innerHTML = '<option value="">Select Sub-District</option>';
    zipcodeInput.value = '';

    fetch('get_sub_districts.php?district_id=' + districtId)
        .then(response => response.json())
        .then(data => {
            data.forEach(subDistrict => {
                subDistrictSelect.innerHTML += `<option value="${subDistrict.id}" data-zipcode="${subDistrict.zip_code}">${subDistrict.name_th}</option>`;
            });
        });
});

document.getElementById('sub_district_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const zipcode = selectedOption.getAttribute('data-zipcode');
    document.getElementById('zipcode').value = zipcode;
});