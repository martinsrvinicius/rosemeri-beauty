<template>
  <va-dropdown class="message-dropdown" position="bottom" :offset="[13, 70]">
    <template #anchor>
      <va-icon-message class="message-dropdown__icon" :class="{ 'message-dropdown__icon--unread': !allRead }" />
    </template>

    <va-dropdown-content class="message-dropdown__content pl-4 pr-4 pt-2 pb-2">
      <div
        v-for="message in messagesProxy"
        :key="message.id"
        class="message-dropdown__item row pt-1 pb-1 mt-2 mb-2"
        :class="{ 'message-dropdown__item--unread': message.unread }"
        @click="openMessage(message.id)"
      >
        <img :src="message.details.avatar" class="message-dropdown__item__avatar mr-2" />
        <span class="ellipsis" style="max-width: 85%">{{
          t(`messages.${message.name}`, { name: message.details.name })
        }}</span>
      </div>
      <div class="row justify--space-between mt-1">
        <va-button class="md6 mr-2" size="small">{{ t('messages.all') }}</va-button>
        <va-button
          class="md6"
          size="small"
          preset="outline"
          border-color="primary"
          :disabled="allRead"
          @click="markAllAsRead"
          >{{ t('messages.mark_as_read') }}</va-button
        >
      </div>
    </va-dropdown-content>
  </va-dropdown>

  <va-modal v-model="showModal" okText="Responder" cancelText="Encerrar" @ok="openNext">
    <va-card>
      <va-card-content class="modal-card-content">
        <div><strong>Nome: &nbsp;</strong> {{ messageClientName }}</div>
        <div><strong>Mensagem: &nbsp; </strong></div>
        {{ messageModal }}
      </va-card-content>
    </va-card>
  </va-modal>

  <va-modal fullscreen v-model="showModal2" okText="Responder" cancelText="Encerrar" @ok="sendEmail">
    <div class="message-container">
      <div><strong>Nome: &nbsp;</strong> {{ messageClientName }}</div>
      <div><strong>Assunto: &nbsp;</strong> {{ subject }}</div>
      <div><strong>Mensagem: &nbsp; </strong>{{ messageModal }}</div>
    </div>

    <va-card>
      <va-card-content class="modal-card-content2">
        <div>
          <strong>De: &nbsp;</strong>
          <va-input v-model="from_email"></va-input>
        </div>
        <div>
          <strong>Para: &nbsp;</strong>
          <va-input v-model="send_to" disabled></va-input>
        </div>
        <div>
          <div>
            <strong>Assunto: &nbsp;</strong>
            <va-input v-model="subject"></va-input>
          </div>
          <strong>Mensagem: &nbsp;</strong>
          <va-input class="text-area" v-model="emailMessage" type="textarea"></va-input>
        </div>
      </va-card-content>
    </va-card>
  </va-modal>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue'
  import { useI18n } from 'vue-i18n'
  import emailjs from '@emailjs/browser'

  import VaIconMessage from '../../../icons/VaIconMessage.vue'
  import { send } from 'vite'

  const { t } = useI18n()
  const showModal = ref(false)
  const showModal2 = ref(false)
  const messageModal = ref()
  const messageClientName = ref()
  const from_email = ref()
  const send_to = ref()
  const subject = ref()
  const emailMessage = ref()
  const to_name = ref()
  const from_name = ref()
  const reply_to = ref()
  const templateParams = ref()

  interface IMessage {
    name: string
    details: {
      name: string
      avatar: string
      message: string
      email: string
      title: string
    }
    unread: boolean
    id: number
  }

  const props = withDefaults(
    defineProps<{
      messages?: IMessage[]
    }>(),
    {
      messages: () => [
        {
          name: 'new',
          details: {
            name: 'Ana Glória',
            avatar: 'https://picsum.photos/24?image=1083',
            message: 'Orçamento de maquilhagem',
            title: 'Agendamento',
            email: 'anagloria.psic@gmail.com',
          },
          unread: true,
          id: 1,
        },
        {
          name: 'new',
          details: {
            name: 'Vinicius Martins',
            avatar: 'https://picsum.photos/24?image=1025',
            message: 'Orçamento',
            title: 'Agendamento',
            email: 'vinim.dev@gmail.com',
          },
          unread: true,
          id: 2,
        },
      ],
    },
  )

  const messagesProxy = ref<IMessage[]>([...props.messages])

  const allRead = computed(() => {
    return messagesProxy.value.every((message) => !message.unread)
  })

  function openMessage(ids: any) {
    showModal.value = !showModal.value
    var filt = messagesProxy.value.filter((item) => item.id == ids)
    filt[0]['unread'] = false
    messageClientName.value = filt[0]['details']['name']
    messageModal.value = filt[0]['details']['message']
    send_to.value = filt[0]['details']['email']
    subject.value = filt[0]['details']['title']
  }

  function openNext() {
    showModal2.value = !showModal2.value
  }

  function clearEmailParams() {
    templateParams.value = {
      subject: '',
      message: '',
      send_to: '',
      to_name: '',
      from_name: '',
      from_email: '',
      reply_to: '',
    }
  }

  function fillEmailParams() {
    templateParams.value = {
      subject: subject.value,
      message: emailMessage.value,
      send_to: send_to.value,
      to_name: messageClientName.value,
      from_name: from_name.value,
      from_email: from_email.value,
      reply_to: reply_to.value,
    }
  }

  function sendEmail() {
    fillEmailParams()
    emailjs.send('service_uu92rmg', 'template_07yssqs', templateParams.value, 'NK14_RXyGbLohutQx').then(
      (response) => {
        console.log('SUCCESS!', response.status, response.text)
        alert('Mensagem enviada!')
        clearEmailParams()
      },
      (err) => {
        console.log('FAILED...', err)
      },
    )
  }

  function markAllAsRead() {
    messagesProxy.value = messagesProxy.value.map((message) => ({
      ...message,
      unread: false,
    }))
  }
</script>

<style lang="scss" scoped>
  .modal-card-content {
    height: 300px;
  }

  .modal-card-content2 {
    min-height: 100px;
    height: 100%;
  }

  .message-container {
    margin-top: 20px;
    margin-bottom: 20px;
  }

  .va-input-wrapper textarea {
    /* margin: 12px 0; */
    resize: vertical;
  }

  .message-dropdown {
    cursor: pointer;

    .message-dropdown__icon {
      position: relative;
      display: flex;
      align-items: center;

      &--unread::before {
        content: '';
        position: absolute;
        right: 0;
        left: 0;
        top: -0.5rem;
        background-color: var(--va-danger);
        height: 0.375rem;
        width: 0.375rem;
        margin: 0 auto;
        border-radius: 0.187rem;
      }
    }

    &__item {
      cursor: pointer;
      margin-bottom: 0.75rem;
      position: relative;
      flex-wrap: nowrap;

      &--unread {
        color: var(--va-gray);

        &::after {
          content: '';
          position: absolute;
          right: 0;
          top: 0;
          bottom: 0;
          height: 0.375rem;
          width: 0.375rem;
          background-color: var(--va-danger);
          margin: auto;
          border-radius: 0.187rem;
        }
      }

      &:hover {
        color: var(--va-primary);
      }

      &__avatar {
        border-radius: 50%;
        min-width: 1.5rem;
        height: 1.5rem;
      }
    }

    .va-dropdown__anchor {
      display: inline-block;
    }
  }
</style>
