const Provinces = {
  districtCodeSelected: 0,
  wardCodeSelected: 0,
  districtList: function () {
    (function ($) {
      $(document).ready(function () {
        $("#city_code").change(function () {
          const cityId = $(this).val().trim();
          if(cityId === '') return;
          $.ajax({
            type: "post",
            url: adminAjaxUrl,
            data: {
              action: "districtList",
              cityId: cityId,
            },
            beforeSend: function () {},
            success: function (response) {
              if (response.success) {
                const arrData = response.data;
                const districtCode = $('#district_code');
                districtCode.empty();
                $.each(arrData, function (index, value) {
                  const selected = Provinces.districtCodeSelected === value.code ? 'selected' : '';
                  districtCode.append('<option '+selected+' value="' + value.code + '">' + value.full_name + '</option>');
                });
                districtCode.trigger('change');
              } else {
                alert("Đã có lỗi xảy ra");
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              //Làm gì đó khi có lỗi xảy ra
              console.log(
                "The following error occured: " + textStatus,
                errorThrown
              );
            },
          });
          return false;
        });
      });
    })(jQuery);
  },



  wardList: function () {
    (function ($) {
      $(document).ready(function () {
        $("#district_code").change(function () {
          const districtId = $(this).val().trim();
          if(districtId === '') return;
          $.ajax({
            type: "post",
            url: adminAjaxUrl,
            data: {
              action: "wardList",
              districtId: districtId,
            },
            beforeSend: function () {},
            success: function (response) {
              if (response.success) {
                const arrData = response.data;
                const wardCode = $('#ward_code');
                wardCode.empty();
                $.each(arrData, function (index, value) {
                  const selected = Provinces.wardCodeSelected === value.code ? 'selected' : '';
                    wardCode.append('<option '+selected+' value="' + value.code + '">' + value.full_name + '</option>');
                });
              } else {
                alert("Đã có lỗi xảy ra");
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              //Làm gì đó khi có lỗi xảy ra
              console.log(
                "The following error occured: " + textStatus,
                errorThrown
              );
            },
          });
          return false;
        });
      });
    })(jQuery);
  },



};
