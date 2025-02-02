[![Build Status](https://travis-ci.org/TheoreticalAstrophysicsGroup/TheoreticalAstrophysicsGroup.github.io.svg?branch=source)](https://travis-ci.org/TheoreticalAstrophysicsGroup/TheoreticalAstrophysicsGroup.github.io)

# Theoretical Astrophysics Group Homepage

This is the Jekyll-powered webpage repo for the Theoretical Astrophysics Group (宇宙物理理論研究室) at the Center for Computational Sciences, University of Tsukuba.

The webpage is meant to give an overview of the members, research, and activities of the group.

The site is almost entirely bilingual (English and Japanese), and the language can be switched (top right menu, or collapsible menu for portable devices) whilst staying on the same page (that is, the corresponding page in the other language is loaded).

The design of the site is based on the [OpenMind](https://wrapbootstrap.com/theme/open-mind-customizable-template-WB0410L74) Bootstrap 4 template by AGM studio.

## Contributing

All members of the group are welcome to contribute via pull requests. The `categories` for posts are:

* `uchu-forum`
* `seminars`  (to be renamed `colloquia` at some point in the near future)
* `projects`
* `news`
* `conferences`
* `featured`  (possibly discontinued in the future)
* `achievements` (Auto-generated ― no need to contribute)
* `members`  (This is actually a "collection", but it's similar to a "category")

The posts themselves are under `<category name>/_posts/[ja|en]`. Tags should be chosen from the entries under `tags:` in `_data/translations.yml`. This will auto-translate them. Feel free to add new tags.

In the near future the site will be linked to [netlify-cms](https://www.netlifycms.org/) so as to allow easier gui based contributing.

## Achievements

Research achievements are a collection of publications that are collated in an ADS library. The posts are semi-autogenerated with a python script that fetches images and creates yaml/html files.

## Members

Member information are stored as Jekyll `collections`. Information of members can be found under `_members/[ja|en]`.

## Contact form

The contact form sends an email to `astro.ccs.tsukuba@gmail.com`, to which the webadmin has access.

## Calendar

The calendar is a google calendar. All staff and several other people of the group (e.g. those responsible for organizing seminars and meetings) have permission to add/edit entries.

## Issues

All work in progress is tracked on the Issues board. Please log any issues you encounter or enhancements you would like to see on the issues board.

## Description (TODO: Needs to be well-writen in English.)
グループ写真変更方法
1. ローカル側でassets/img/group.jpgを、別の写真に変える

2. Build and deploy

宇宙フォーラム更新方法
1. charon側で
uchu_forum/uchu_forum_form/img
uchu_forum/uchu_forum_form/yaml/[ja,en]
から
hogehoge.[jpg, png, etc]と
20xx-xx-xx-hoge.htmlを取り出す

scp -r <user_name>@charon.ccs.tsukuba.ac.jp:/home-WWW/Research/Astro/Astro_source/uchu_forum/uchu_forum_form/yml __temp/yml
scp -r <user_name>@charon.ccs.tsukuba.ac.jp:/home-WWW/Research/Astro/Astro_source/uchu_forum/uchu_forum_form/img __temp/img

2.  ローカル側でuchu_forum/_posts/[ja,en]に20xx-xx-xx-hoge.htmlを配置(enに配置するやつは英訳)
ローカル側でassets/img/uchu_forumにhogehoge.[jpg, png, etc]をuchu-forum-20xx-xx-xx.jpgとして保存し、
その画像を200×200のサイズに切り取った画像をuchu-forum-20xx-xx-xx-thumb.jpgとして保存
https://www.iloveimg.com/ja/crop-image
このサイトで切り抜きできる(どうせ外部に公開するものだからセキュリティはあまり考えなくて良い)

3. Build and deploy

新メンバー追加方法

1. ローカル側でmembers/membresform/[ja,en]/index.htmlのform_activeをfalseからtrueに
ローカル側でmembers/[ja,en]/index.htmlのform_activeをfalseからtrueに

2. build and deploy

3. 新入生と既存の学生全員にフォームを入力してもらう

4. 
