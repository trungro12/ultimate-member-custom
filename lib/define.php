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


