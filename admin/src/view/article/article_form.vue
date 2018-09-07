<template>
  <Card>
    <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80" style="width: 70%;">
      <FormItem label="栏目分类" prop="cate_id">
        <Select v-model="formValidate.cate_id" placeholder="请选择栏目">
          <Option v-for="item in cityList" :value="item.id" :key="item.id">{{ item.label }}</Option>
        </Select>
      </FormItem>
      <FormItem label="标题" prop="title">
        <Input v-model="formValidate.title" placeholder="请输入标题"></Input>
      </FormItem>
      <FormItem label="封面图" prop="photo">
        <Upload
          :action="this.photo.action"
          :name="this.photo.name"
          :data="this.photo.data"
          :accept="this.photo.accept"
          :format=this.photo.format
          :max-size=this.photo.maxsize
          :ref="this.photo.name"
          :show-upload-list=this.photo.show_list
          :on-error="photoUploadError"
          :on-success="photoUploadSuccess"
          :on-preview="photoPreview"
          :on-format-error="photoUploadFormatError"
          :on-exceeded-size="photoUploadSizeError"
          :before-upload="photoBeforeUpload"
        >
          <Button icon="ios-cloud-upload-outline">上传图片</Button>
        </Upload>
      </FormItem>
      <FormItem id="photo_view_item" style="display: none;">
        <img src="" alt="" id="photo_view" style="width: 100px;">
      </FormItem>
      <FormItem label="状态" prop="status_switch">
        <i-switch size="large" v-model="formValidate.status_switch" prop="status">
          <span slot="open">启用</span>
          <span slot="close">禁用</span>
        </i-switch>
      </FormItem>
      <FormItem label="置顶" prop="istop_switch">
        <i-switch size="large" v-model="formValidate.istop_switch" prop="istop">
          <span slot="open">是</span>
          <span slot="close">否</span>
        </i-switch>
      </FormItem>
      <FormItem label="排序值" prop="sort">
        <Input v-model="formValidate.sort" placeholder="请输入排序值"></Input>
      </FormItem>
      <FormItem label="摘要" prop="summary">
        <Input v-model="formValidate.summary" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请填写文章摘要，用于列表展示"></Input>
      </FormItem>
      <FormItem label="内容" prop="content">
        <div class="editor-wrapper">
          <div id="editor"></div>
        </div>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="handleSubmit('formValidate')">保存</Button>
        <Button @click="handleReset('formValidate')" style="margin-left: 8px">重置</Button>
      </FormItem>
    </Form>
  </Card>
</template>
<script>
import { getCategoryData, addArticleData, getArticleInfo } from '@/api/data'
import { getToken } from '@/libs/util'
import Editor from 'wangeditor'
import 'wangeditor/release/wangEditor.min.css'
import {Message} from 'iview'
import config from '@/config'
export default {
  data () {
    const validateTitle = (rule, value, callback) => {
      if (value.length > 50) {
        callback(new Error('标题长度最多50个字符'))
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
        cate_id: 0,
        title: '',
        sort: 0,
        status_switch: true,
        istop_switch: false,
        content: '',
        summary: ''
      },
      cityList: [],
      photo: {
        action: config.upload,
        name: 'photo',
        accept: 'image/*',
        data: {
          token: getToken()
        },
        format: ['jpg', 'jpeg', 'png'],
        maxsize: 1024,
        show_list: true
      },
      edit_id: this.$route.params.id,
      ruleValidate: {
        cate_id: [
          { required: true, type: 'number', min: 1, message: '请选择栏目分类', trigger: 'change' }
        ],
        title: [
          { required: true, message: '标题不能为空', trigger: 'blur' },
          { validator: validateTitle, trigger: 'blur' }
        ],
        sort: [
          { validator: validateSort, trigger: 'blur' }
        ],
        photo: [
          { type: 'url', message: '图片地址有误', trigger: 'blur' }
        ],
        content: [
          { required: true, message: '内容不能为空', trigger: 'blur' }
        ],
        summary: [
          { required: true, message: '摘要信息不能为空', trigger: 'blur' },
          { type: 'string', max: 50, message: '不超过50个字符', trigger: 'blur' }
        ]
      }
    }
  },
  methods: {
    loading () {
      this.$Message.loading({
        content: 'Loading...',
        duration: 0
      })
    },
    handleSubmit (name) {
      this.formValidate.content = this.editor.txt.html()
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.formValidate.token = getToken()
          this.formValidate.status = this.formValidate.status_switch ? '1' : '-1'
          this.formValidate.is_top = this.formValidate.istop_switch ? '1' : '0'
          addArticleData(this.formValidate).then(res => {
            if (res.errcode === 0) {
              this.$Message.success({
                content: 'Success!',
                duration: 2
              })
            } else {
              this.$Message.error({
                content: res.errmsg,
                duration: 3
              })
            }
          })
        }
      })
    },
    handleReset (name) {
      this.$refs[name].resetFields()
    },
    photoBeforeUpload (file) {
      this.loading()
    },
    photoUploadError (error, file, fileList) {
      this.$Message.destroy()
      this.$Message.error({
        content: file.errmsg,
        duration: 3
      })
    },
    photoUploadSuccess (response, file, fileList) {
      this.$refs.photo.clearFiles()
      this.$refs.photo.addFile(file)
      this.$Message.destroy()
      if (response.errcode === 0) {
        this.$Message.success('上传成功 ~~')
        this.formValidate.photo = response.data.url
        document.querySelector('#photo_view_item').setAttribute('style', 'display:none;')
      } else {
        this.$Message.error({
          content: response.errmsg,
          duration: 3
        })
      }
    },
    photoPreview (file) {
      var url = file.response.data.url
      window.open(url)
    },
    photoUploadFormatError (file) {
      this.$Message.destroy()
      this.$Message.error({
        content: file.name + ' 格式不正确',
        duration: 3
      })
    },
    photoUploadSizeError (file) {
      this.$Message.destroy()
      this.$Message.error({
        content: file.name + ' 最大只允许1M',
        duration: 3
      })
    }
  },
  mounted () {
    getCategoryData().then(res => {
      this.cityList = res.data
    })

    this.editor = new Editor(`#editor`)
    this.editor.customConfig.onchange = (html) => {
      let text = this.editor.txt.text()
      this.$emit('input', this.valueType === 'html' ? html : text)
      this.$emit('on-change', html, text)
    }
    this.editor.customConfig.onchangeTimeout = this.changeInterval
    this.editor.customConfig.uploadImgServer = this.photo.action
    this.editor.customConfig.uploadImgMaxLength = 1
    this.editor.customConfig.uploadImgMaxSize = 1 * 1024 * 1024
    this.editor.customConfig.uploadFileName = 'photo'
    this.editor.customConfig.uploadImgParams = {
      token: getToken()
    }
    this.editor.customConfig.uploadImgHooks = {
      customInsert: function (insertImg, result, editor) {
        if (result.errcode === 0) {
          var url = result.data.url
          insertImg(url)
        } else {
          Message.error({
            content: result.errmsg,
            duration: 3
          })
        }
      }
    }
    this.editor.customConfig.menus = [
      'head',
      'bold',
      'fontSize',
      'fontName',
      'italic',
      'underline',
      'strikeThrough',
      'foreColor',
      'backColor',
      'link',
      'list',
      'justify',
      'quote',
      'image',
      'table',
      'code'
    ]
    // create这个方法一定要在所有配置项之后调用
    this.editor.create()

    if (this.edit_id) {
      this.formValidate.id = this.edit_id
      getArticleInfo(this.formValidate.id).then(res => {
        if (res.errcode === 0) {
          const data = res.data
          this.formValidate.cate_id = data.cate_id
          this.formValidate.title = data.title
          this.formValidate.sort = data.sort
          this.formValidate.summary = data.summary
          this.formValidate.status_switch = data.status === 1
          this.formValidate.istop_switch = data.is_top === 1
          this.editor.txt.html(data.content)
          if (data.photo) {
            this.formValidate.photo = data.photo
            document.querySelector('#photo_view').setAttribute('src', data.photo)
            document.querySelector('#photo_view_item').setAttribute('style', 'display:block;')
          }
        } else {
          Message.error({
            content: res.errmsg,
            duration: 3
          })
        }
      })
    }
  }
}
</script>
