<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2010 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class about_this_photo_block_Core {

  static function get_site_list() {
    return array("simple" => t("About This Photo"));
  }

  static function get($block_id, $theme) {
    $block = new Block();
    switch ($block_id) {
    case "simple":
      $block->css_id = "g-about-this-photo";
      $block->title = t("About this photo");
      $block->content = new View("about_this_photo.html");

      // exif API doesn't give easy access to individual keys, so do this the hard way
      if (module::is_active("exif")) {
        $exif = ORM::factory("exif_record")->where("item_id", "=", $theme->item()->id)->find();
        if ($exif->loaded()) {
          $exif = unserialize($exif->data);
          $timestamp = strtotime($exif["DateTime"]);
          $block->content->date = gallery::date($timestamp);
          $block->content->time = gallery::time($timestamp);
        }
      }

      if (module::is_active("tag")) {
        $block->content->tags = tag::item_tags($theme->item());
      }
      break;
    }
    return $block;
  }
}