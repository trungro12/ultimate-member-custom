<?php



// business_type, loai hinh o form dang ky
const UMC_BUSINESS_TYPE_PHARMACY =  1; // 'Nhà thuốc';
const UMC_BUSINESS_TYPE_HEALTH_CENTER = 2; //'Trung tâm y tế';
const UMC_BUSINESS_TYPE_CLINIC = 3; //'Phòng khám';
const UMC_BUSINESS_TYPE_DENTISTRY = 4; //'Nha khoa';
const UMC_BUSINESS_TYPE_DRUGSTORE = 5; //'Quầy thuốc';
const UMC_BUSINESS_TYPE_BEAUTY_SALON = 6; //'Thẩm mỹ viện';
const UMC_BUSINESS_TYPE_HOSPITAL = 7; //'Bệnh viện';
const UMC_BUSINESS_TYPE_PHARMA_COMPANY = 8; //'Công ty Dược phẩm';
const UMC_BUSINESS_TYPE_PATIENT = 9; //'Bệnh nhân';

// == LOẠI GIẤY TỜ
// NHÀ THUỐC
const UMC_BUSINESS_PHARMACY_DOCUMENT_LICENSE = 1; // GIẤY PHÉP KINH DOANH
const UMC_BUSINESS_PHARMACY_DOCUMENT_GPP = 2; // HỒ SƠ GPP
const UMC_BUSINESS_PHARMACY_DOCUMENT_CERTIFICATE_OF_QUALIFICATION_FOR_PHARMACEUTICAL = 3; // GIẤY CHỨNG NHẠN ĐỦ ĐIỀU KIỆN KINH DOANH DƯỢC





function umcGetListBusinessType(){
    return [
        UMC_BUSINESS_TYPE_PHARMACY => 'Nhà thuốc',
        UMC_BUSINESS_TYPE_HEALTH_CENTER => 'Trung tâm y tế',
        UMC_BUSINESS_TYPE_CLINIC => 'Phòng khám',
        UMC_BUSINESS_TYPE_DENTISTRY => 'Nha khoa',
        UMC_BUSINESS_TYPE_DRUGSTORE => 'Quầy thuốc',
        UMC_BUSINESS_TYPE_BEAUTY_SALON => 'Thẩm mỹ viện',
        UMC_BUSINESS_TYPE_HOSPITAL => 'Bệnh viện',
        UMC_BUSINESS_TYPE_PHARMA_COMPANY => 'Công ty Dược phẩm',
        UMC_BUSINESS_TYPE_PATIENT => 'Bệnh nhân',
    ];
}

function umcGetListDocumentType($businessType = 1){
    $arr = [
        UMC_BUSINESS_TYPE_PHARMACY => [
            UMC_BUSINESS_PHARMACY_DOCUMENT_LICENSE => 'Giấy phép kinh doanh',
            UMC_BUSINESS_PHARMACY_DOCUMENT_GPP => 'Hồ sơ GPP',
            UMC_BUSINESS_PHARMACY_DOCUMENT_CERTIFICATE_OF_QUALIFICATION_FOR_PHARMACEUTICAL => 'Giấy chứng nhận đủ điều kiện kinh doanh dược',
        ]
    ];

    return $arr[$businessType];
}


