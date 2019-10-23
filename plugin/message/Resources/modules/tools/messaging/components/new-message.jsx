import React from 'react'
import {connect} from 'react-redux'
import {PropTypes as T} from 'prop-types'

import {withRouter} from '#/main/app/router'
import {trans} from '#/main/app/intl/translation'
import {CALLBACK_BUTTON} from '#/main/app/buttons'
import {selectors as securitySelectors} from '#/main/app/security/store'
import {actions as formActions} from '#/main/app/content/form/store/actions'
import {FormData} from '#/main/app/content/form/containers/data'
import {Button} from '#/main/app/action/components/button'

import {selectors as toolSelectors} from '#/main/core/tool/store'
import {User as UserTypes} from '#/main/core/user/prop-types'
import {UserAvatar} from '#/main/core/user/components/avatar'

import {selectors} from '#/plugin/message/tools/messaging/store'

const NewMessageFormWrapper = (props) =>
  <div className="user-message-container user-message-form-container user-message-left">
    <UserAvatar picture={props.user.picture} alt={false} />

    <div className="user-message">
      <div className="user-message-meta">
        <div className="user-message-info">
          {props.user.name}
        </div>
      </div>

      <div className="user-message-content embedded-form-section">
        {props.children}
      </div>

      <Button
        className="btn btn-block btn-save btn-emphasis"
        label={trans('send', {}, 'actions')}
        type={CALLBACK_BUTTON}
        callback={props.callback}
        primary={true}
      />
    </div>
  </div>


NewMessageFormWrapper.propTypes = {
  user: T.shape(UserTypes.propTypes),
  callback: T.func.isRequired,
  children: T.node.isRequired
}

const NewMessageComponent = (props) =>
  <NewMessageFormWrapper
    user={props.currentUser}
    callback={() => props.saveForm(props.history.push, props.path)}
  >
    <FormData
      level={3}
      displayLevel={2}
      name={`${selectors.STORE_NAME}.messageForm`}
      sections={[
        {
          title: 'message',
          fields:[
            {
              name: 'receivers.users',
              type: 'users',
              label: trans('message_form_to', {}, 'message')
            }, {
              name: 'receivers.groups',
              type: 'groups',
              label: trans('message_form_to', {}, 'message')
            }, {
              name: 'receivers.workspaces',
              type: 'workspaces',
              label: trans('message_form_to', {}, 'message')
            }, {
              name: 'object',
              type: 'string',
              label: trans('message_form_object', {}, 'message')
            }, {
              name: 'content',
              type: 'html',
              label: trans('message_form_content', {}, 'message'),
              required: true
            }
          ]
        }
      ]}
    />
  </NewMessageFormWrapper>

NewMessageComponent.propTypes = {
  currentUser: T.shape({
    // TODO
  }).isRequired,
  saveForm: T.func.isRequired,
  path: T.string.isRequired,
  history: T.shape({
    push: T.func.isRequired
  }).isRequired
}

const NewMessage = withRouter(connect(
  state => ({
    currentUser: securitySelectors.currentUser(state),
    path: toolSelectors.path(state)
  }),
  (dispatch) => ({
    saveForm(push, path) {
      dispatch(formActions.saveForm(
        `${selectors.STORE_NAME}.messageForm`,
        ['apiv2_message_create']
      )).then(() => push(`${path}/received`))
    }
  })
)(NewMessageComponent))

export {
  NewMessage
}
