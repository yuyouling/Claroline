import {makeFormReducer} from '#/main/app/content/form/store'

import {selectors} from '#/main/core/tools/locations/room/modals/booking/store/selectors'

export const reducer = makeFormReducer(selectors.STORE_NAME)
