<template>
  <Card>
    <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80" style="width: 70%;">
      <FormItem label="栏目名称" prop="name">
        <Input v-model="formValidate.name" placeholder="请输入栏目名称"></Input>
      </FormItem>
      <FormItem label="排序值" prop="sort">
        <Input v-model="formValidate.sort" placeholder="请输入排序值"></Input>
      </FormItem>
      <FormItem label="状态" prop="switch">
        <i-switch size="large" v-model="formValidate.switch" prop="switch">
          <span slot="open">启用</span>
          <span slot="close">禁用</span>
        </i-switch>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="handleSubmit('formValidate')">保存</Button>
        <Button @click="handleReset('formValidate')" style="margin-left: 8px">重置</Button>
      </FormItem>
    </Form>
  </Card>
</template>
<script>
import { addCategoryData, getCategoryInfo } from '@/api/data'
import { getToken } from '@/libs/util'
export default {
  data () {
    const validateName = (rule, value, callback) => {
      if (value.length > 20) {
        callback(new Error('栏目名称长度最多20个字符'))
      } else {
        callback()
      }
    }
    const validateSort = (rule, value, callback) => {
      var r = /^-?\d+$/
      var flag = r.test(value)
      if (flag) {
        callback()
      } else {
        callback(new Error('排序值必须为有效的整数'))
      }
    }
    return {
      formValidate: {
        name: '',
        sort: '0',
        switch: true
      },
      edit_id: this.$route.params.id,
      ruleValidate: {
        name: [
          { required: true, message: '栏目名称不能为空', trigger: 'blur' },
          { validator: validateName, trigger: 'blur' }
        ],
        sort: [
          { validator: validateSort, trigger: 'blur' }
        ]
      }
    }
  },
  methods: {
    handleSubmit (name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          const token = getToken()
          this.formValidate.token = token
          this.formValidate.status = this.formValidate.switch ? '1' : '-1'
          addCategoryData(this.formValidate).then(res => {
            if (res) {
              this.$Message.success('Success!')
            }
          })
        }
      })
    },
    handleReset (name) {
      this.$refs[name].resetFields()
    }
  },
  mounted () {
    if (this.$route.params.id) {
      this.formValidate.id = this.$route.params.id
      getCategoryInfo(this.formValidate.id).then(res => {
        const data = res.data
        this.formValidate.name = data.name
        this.formValidate.sort = data.sort
        this.formValidate.switch = data.status === 1
      })
    }
  }
}
</script>
