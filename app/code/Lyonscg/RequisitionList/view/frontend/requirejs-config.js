var config = {
    "config": {
        "mixins": {
            "Magento_RequisitionList/js/requisition/action/product/add": {
                "Lyonscg_RequisitionList/js/requisition/action/product/add-mixin": true
            }
        }
    },
    "map" : {
        '*': {
            "lyonscgRequisitionListGrid": 'Lyonscg_RequisitionList/js/grid/listing',
            'Magento_RequisitionList/js/requisition/list/edit/modal': 'Lyonscg_RequisitionList/js/requisition/list/edit/modal',
        }
    }
};
