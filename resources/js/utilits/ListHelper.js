import { router,usePage } from '@inertiajs/vue3'

class ListHelper {
    sortBy(fieldName,sortBy){
        let shortByy = sortBy == -1 ? 'desc' :'asc';
        router.reload({
            method: 'get',
            data: {fieldName:fieldName ,sortBy: shortByy},
            replace: true,
        });
    }

    setPerPage(perPage) {
        router.reload({
            method: 'get',
            data: { perPage: perPage },
            replace: false,
        });
    }

    setPageNum(page) {
        router.reload({
            method: 'get',
            data: {page:page, },
            replace: true,
        });
    }
    setOnPage(page,perPage) {
        router.reload({
            method: 'get',
            data: {page:page, perPage: perPage  },
            replace: true,
        });
    }
}

export default ListHelper = new ListHelper()
