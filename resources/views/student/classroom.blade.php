@extends('layouts.app')

@section('content')
<!-- ==================== TOP NAVIGATION BAR ==================== -->
<header class="flex justify-between items-center w-full px-6 h-16 border-b border-slate-200 sticky top-0 z-50 bg-white shadow-xs">
    <!-- Left Section: Back to Dashboard & Breadcrumbs -->
    <div class="flex items-center gap-4">
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-indigo-600 transition-colors group" 
           href="{{ route('student.dashboard') }}" title="Kembali ke Dasbor">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span class="text-xs font-semibold hidden sm:inline">Kembali ke Dasbor</span>
        </a>

        <div class="h-5 w-px bg-slate-200 mx-1 hidden md:block"></div>

        <!-- Compact Breadcrumb Trail -->
        <nav aria-label="Breadcrumb" class="hidden sm:flex items-center gap-1.5 text-slate-500 text-xs font-medium">
            <a class="hover:text-indigo-600 transition-colors" href="{{ route('student.dashboard') }}">Kursus</a>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <a class="hover:text-indigo-600 transition-colors max-w-[180px] truncate" href="{{ route('student.classroom', $course->slug) }}">{{ $course->title }}</a>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <span class="text-slate-900 font-semibold truncate max-w-[200px]">{{ $currentLesson->module_title ?: 'Modul Materi' }}</span>
        </nav>
    </div>

    <!-- Center/Right Navigation Links & Actions -->
    <div class="flex items-center gap-3">
        @if($certificate)
            <a href="{{ route('student.certificate', $certificate->certificate_code) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all animate-pulse"
               title="Kursus Telah Selesai 100%! Buka Sertifikat Resmi Anda">
                <span class="material-symbols-outlined text-[17px]">workspace_premium</span>
                <span>Lihat & Cetak Sertifikat</span>
            </a>
        @endif

        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition-all">
            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
            <span class="hidden md:inline">Portal Admin</span>
        </a>

        <div class="h-6 w-px bg-slate-200"></div>

        <!-- Instructor/Student Account Menu -->
        <div class="flex items-center gap-2.5 pl-1">
            <div class="w-8 h-8 rounded-full ring-1 ring-slate-200 overflow-hidden bg-slate-100">
                <img class="w-full h-full object-cover" 
                     src="{{ $student->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}" 
                     alt="Foto Siswa"/>
            </div>
            <div class="hidden lg:flex flex-col text-left leading-none">
                <span class="text-xs font-semibold text-slate-900">{{ $student->name }}</span>
                <span class="text-[11px] text-slate-500">{{ $student->school_name ?: 'Siswa EduTube' }}</span>
            </div>
        </div>
    </div>
</header>

<!-- ==================== MAIN LEARNING VIEW (SPLIT SCREEN) ==================== -->
<main class="flex-1 w-full max-w-[1720px] mx-auto px-4 sm:px-6 lg:px-8 py-6"
      @lesson-video-ended.window="isCompleted = true; if($event.detail) { if($event.detail.progress !== undefined) progress = $event.detail.progress; if($event.detail.completed_count !== undefined) completedCount = $event.detail.completed_count; if($event.detail.passed_quizzes_count !== undefined) passedQuizzes = $event.detail.passed_quizzes_count; if($event.detail.has_passed_all_quizzes !== undefined) hasPassedQuizzes = $event.detail.has_passed_all_quizzes; if($event.detail.certificate) { certificateUrl = $event.detail.certificate.url; showGraduationModal = true; } }"
      x-data="{ 
          activeTab: 'overview',
          isCompleted: {{ $isCompleted ? 'true' : 'false' }},
          isBookmarked: {{ $isBookmarked ? 'true' : 'false' }},
          progress: {{ $progressPercentage }},
          completedCount: {{ $completedLessonsCount }},
          totalCount: {{ $totalLessonsCount }},
          totalQuizzes: {{ $totalQuizzesCount }},
          passedQuizzes: {{ $passedQuizzesCount }},
          hasPassedQuizzes: {{ $hasPassedAllQuizzes ? 'true' : 'false' }},
          lastWatchedSecond: {{ (int) $lastWatchedSecond }},
          certificateUrl: '{{ $certificate ? route('student.certificate', $certificate->certificate_code) : '' }}',
          showGraduationModal: false,

          // Auto-Next State & Modal
          showAutoNextToast: false,
          autoNextCountdown: 5,
          autoNextInterval: null,

          // Notes State
          notes: {{ Js::from($notes->map(fn($n) => [
              'id' => $n->id,
              'content' => $n->content,
              'timestamp_seconds' => $n->timestamp_seconds,
              'formatted_timestamp' => $n->formatted_timestamp,
              'created_at' => $n->created_at?->diffForHumans() ?? 'Baru saja'
          ])) }},
          newNoteContent: '',
          newNoteTimestampStr: '',
          isSavingNote: false,

          // Discussions State
          comments: {{ Js::from($comments->map(fn($c) => [
              'id' => $c->id,
              'user_name' => $c->user_name,
              'user_avatar' => $c->user_avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
              'timestamp_str' => $c->timestamp_str,
              'content' => $c->content,
              'likes_count' => $c->likes_count,
              'is_liked' => $c->isLikedBy($student),
              'created_at' => $c->created_at?->diffForHumans() ?? 'Baru saja',
              'show_reply_box' => false,
              'reply_content' => '',
              'replies' => $c->replies->map(fn($r) => [
                  'id' => $r->id,
                  'user_name' => $r->user_name,
                  'user_avatar' => $r->user_avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
                  'content' => $r->content,
                  'likes_count' => $r->likes_count,
                  'is_liked' => $r->isLikedBy($student),
                  'created_at' => $r->created_at?->diffForHumans() ?? 'Baru saja',
              ])
          ])) }},
          newCommentContent: '',
          newCommentTimestamp: '',
          isSubmittingComment: false,

          // Quiz State
          quizzes: {{ Js::from($quizzes->map(function ($q) use ($quizAttempt) {
              $answers = $quizAttempt && is_array($quizAttempt->answers) ? $quizAttempt->answers : [];
              $selected = array_key_exists($q->id, $answers) ? (int) $answers[$q->id] : null;
              return [
                  'id' => $q->id,
                  'question' => $q->question,
                  'options' => $q->options,
                  'correct_answer_index' => $q->correct_answer_index,
                  'explanation' => $q->explanation,
                  'selected_option' => $selected,
                  'is_answered' => $selected !== null
              ];
          })) }},
          quizAttemptScore: {{ $quizAttempt ? $quizAttempt->score : 'null' }},
          quizAttemptPassed: {{ $quizAttempt ? ($quizAttempt->passed ? 'true' : 'false') : 'null' }},
          isSubmittingQuiz: false,

          // Resources State
          resources: {{ Js::from($resources->map(fn($r) => [
              'id' => $r->id,
              'title' => $r->title,
              'type' => $r->type,
              'url' => $r->url,
              'icon' => $r->icon,
          ])) }},

          init() {
              window.addEventListener('lesson-video-ended', () => {
                  this.handleVideoEnded();
              });
          },

          handleVideoEnded() {
              this.isCompleted = true;
              @if($nextLesson)
                  this.showAutoNextToast = true;
                  this.autoNextCountdown = 5;
                  if (this.autoNextInterval) clearInterval(this.autoNextInterval);
                  this.autoNextInterval = setInterval(() => {
                      this.autoNextCountdown--;
                      if (this.autoNextCountdown <= 0) {
                          clearInterval(this.autoNextInterval);
                          window.location.href = '{{ route('student.classroom.lesson', [$course->slug, $nextLesson->id]) }}';
                      }
                  }, 1000);
              @endif
          },

          cancelAutoNext() {
              if (this.autoNextInterval) clearInterval(this.autoNextInterval);
              this.showAutoNextToast = false;
          },

          proceedAutoNextNow() {
              if (this.autoNextInterval) clearInterval(this.autoNextInterval);
              @if($nextLesson)
                  window.location.href = '{{ route('student.classroom.lesson', [$course->slug, $nextLesson->id]) }}';
              @endif
          },

          toggleComplete() {
              fetch('{{ route('student.lesson.toggle', $currentLesson->id) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(data => {
                  this.isCompleted = data.completed;
                  this.progress = data.progress;
                  this.completedCount = data.completed_count;
                  if (data.passed_quizzes_count !== undefined) {
                      this.passedQuizzes = data.passed_quizzes_count;
                  }
                  if (data.has_passed_all_quizzes !== undefined) {
                      this.hasPassedQuizzes = data.has_passed_all_quizzes;
                  }
                  if (data.certificate) {
                      this.certificateUrl = data.certificate.url;
                      this.showGraduationModal = true;
                  }
              });
          },

          toggleCourseBookmark() {
              fetch('{{ route('student.course.bookmark', $course->slug) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(data => {
                  this.isBookmarked = data.bookmarked;
              });
          },

          seekVideo(seconds) {
              if (window.ytPlayer && typeof window.ytPlayer.seekTo === 'function') {
                  window.ytPlayer.seekTo(Number(seconds) || 0, true);
              }
          },

          parseTimestampToSeconds(str) {
              if (!str) return 0;
              const parts = str.trim().split(':');
              if (parts.length === 2) {
                  return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10);
              }
              return parseInt(str, 10) || 0;
          },

          saveNote() {
              if (!this.newNoteContent.trim()) return;
              this.isSavingNote = true;
              const seconds = this.parseTimestampToSeconds(this.newNoteTimestampStr);

              fetch('{{ route('student.lesson.notes.store', $currentLesson->id) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify({
                      content: this.newNoteContent,
                      timestamp_seconds: seconds
                  })
              })
              .then(res => res.json())
              .then(data => {
                  if (data.note) {
                      this.notes.unshift({
                          id: data.note.id,
                          content: data.note.content,
                          timestamp_seconds: data.note.timestamp_seconds,
                          formatted_timestamp: data.formatted_timestamp,
                          created_at: 'Baru saja'
                      });
                      this.newNoteContent = '';
                      this.newNoteTimestampStr = '';
                  }
                  this.isSavingNote = false;
              })
              .catch(() => { this.isSavingNote = false; });
          },

          deleteNote(noteId) {
              fetch(`/notes/${noteId}`, {
                  method: 'DELETE',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(() => {
                  this.notes = this.notes.filter(n => n.id !== noteId);
              });
          },

          submitComment(parentId = null, replyContent = '') {
              const text = parentId ? replyContent : this.newCommentContent;
              if (!text.trim()) return;
              this.isSubmittingComment = true;

              fetch('{{ route('student.lesson.comments.store', $currentLesson->id) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify({
                      content: text,
                      timestamp_str: this.newCommentTimestamp ? ('pada ' + this.newCommentTimestamp) : 'pada diskusi',
                      parent_id: parentId
                  })
              })
              .then(res => res.json())
              .then(data => {
                  if (data.comment) {
                      const newObj = {
                          id: data.comment.id,
                          user_name: data.comment.user_name,
                          user_avatar: data.comment.user_avatar || '{{ $student->avatar_url }}',
                          timestamp_str: data.comment.timestamp_str,
                          content: data.comment.content,
                          likes_count: 0,
                          is_liked: false,
                          created_at: 'Baru saja',
                          show_reply_box: false,
                          reply_content: '',
                          replies: []
                      };

                      if (parentId) {
                          const parent = this.comments.find(c => c.id === parentId);
                          if (parent) {
                              parent.replies.push(newObj);
                              parent.show_reply_box = false;
                              parent.reply_content = '';
                          }
                      } else {
                          this.comments.unshift(newObj);
                          this.newCommentContent = '';
                          this.newCommentTimestamp = '';
                      }
                  }
                  this.isSubmittingComment = false;
              })
              .catch(() => { this.isSubmittingComment = false; });
          },

          toggleLike(commentObj) {
              fetch(`/komentar/${commentObj.id}/like`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(data => {
                  commentObj.is_liked = data.liked;
                  commentObj.likes_count = data.likes_count;
              });
          },

          selectQuizAnswer(quizIndex, optionIndex) {
              const quiz = this.quizzes[quizIndex];
              quiz.selected_option = optionIndex;
          },

          submitAllQuizAnswers() {
              const answersPayload = {};
              for (const q of this.quizzes) {
                  if (q.selected_option !== null) {
                      answersPayload[q.id] = q.selected_option;
                  }
              }

              if (Object.keys(answersPayload).length < this.quizzes.length) {
                  alert('Harap jawab semua soal kuis checkpoint sebelum mengirimkan.');
                  return;
              }

              this.isSubmittingQuiz = true;

              fetch('{{ route('student.lesson.quiz', $currentLesson->id) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify({ answers: answersPayload })
              })
              .then(res => res.json())
              .then(data => {
                  this.isSubmittingQuiz = false;
                  if (data.success) {
                      this.quizAttemptScore = data.score;
                      this.quizAttemptPassed = data.passed;
                      for (const q of this.quizzes) {
                          q.is_answered = true;
                      }
                      if (data.passed_quizzes_count !== undefined) {
                          this.passedQuizzes = data.passed_quizzes_count;
                      }
                      if (data.has_passed_all_quizzes !== undefined) {
                          this.hasPassedQuizzes = data.has_passed_all_quizzes;
                      }
                      if (data.certificate) {
                          this.certificateUrl = data.certificate.url;
                          this.showGraduationModal = true;
                      }
                  }
              })
              .catch(() => {
                  this.isSubmittingQuiz = false;
              });
          }
      }">

    <!-- ==================== AUTO-NEXT 5-SECOND COUNTDOWN TOAST ==================== -->
    <div x-show="showAutoNextToast" 
         x-cloak
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white p-4 rounded-xl shadow-xl border border-slate-800 max-w-sm flex flex-col gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">task_alt</span>
            </div>
            <div>
                <h4 class="text-xs font-bold text-white">Materi Berhasil Diselesaikan!</h4>
                <p class="text-[11px] text-slate-300">
                    Memutar materi berikutnya dalam <strong class="text-emerald-400 text-xs font-mono" x-text="autoNextCountdown + ' detik'"></strong>.
                </p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-800">
            <button type="button" @click="cancelAutoNext()" class="px-3 py-1 rounded text-xs font-medium text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                Batal
            </button>
            <button type="button" @click="proceedAutoNextNow()" class="px-3.5 py-1 rounded bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-colors flex items-center gap-1 shadow-sm">
                <span>Lanjut Sekarang</span>
                <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- ==================== LEFT COLUMN (70% - 8/12 cols) ==================== -->
        <section class="lg:col-span-8 flex flex-col gap-6">
            <!-- Embedded 16:9 Modern Video Player UI Frame with YouTube IFrame API -->
            <div class="relative w-full aspect-video bg-[#0A192F] rounded-xl overflow-hidden border border-[#1E293B] shadow-lg flex flex-col justify-between group select-none">
                <!-- YouTube Video iFrame with id for API message control -->
                <iframe id="youtube-player"
                        class="w-full h-full aspect-video absolute inset-0 z-10 border-0" 
                        src="https://www.youtube-nocookie.com/embed/{{ $currentLesson->youtube_video_id }}?autoplay=1&enablejsapi=1&rel=0&modestbranding=1" 
                        title="{{ $currentLesson->title }}" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                </iframe>
            </div>

            <!-- ==================== LESSON DETAILS & ACTION HEADER ==================== -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col gap-5 shadow-xs">
                <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-4">
                    <div class="flex flex-col gap-2.5">
                        <!-- Meta Badges & Course Status Pill -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                <span class="material-symbols-outlined text-[14px]">school</span>
                                {{ $course->category->name ?? 'Pemrograman' }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                                <span class="material-symbols-outlined text-[14px]">apartment</span>
                                {{ $course->school_name ?: 'EduTube Pusat' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                                {{ $currentLesson->module_title }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs text-slate-400 font-mono">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                {{ $currentLesson->duration_formatted }}
                            </span>
                        </div>

                        <!-- Main Lesson Title -->
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug">
                            {{ $currentLesson->title }}
                        </h1>

                        <!-- Instructor Avatar & Bio Line -->
                        <div class="flex items-center gap-3 pt-1">
                            <div class="w-9 h-9 rounded-full overflow-hidden ring-1 ring-slate-200 bg-slate-100">
                                <img class="w-full h-full object-cover" 
                                     src="{{ $course->instructor_avatar ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80' }}" 
                                     alt="{{ $course->instructor_name }}"/>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-900">{{ $course->instructor_name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $course->instructor_title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Primary & Secondary Interactive Action CTAs -->
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 shrink-0 self-start pt-2 xl:pt-0">
                        <!-- Bookmark Course Button -->
                        <button type="button" @click="toggleCourseBookmark()" 
                                :class="isBookmarked ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border text-xs font-semibold transition-all shadow-xs"
                                :title="isBookmarked ? 'Tersimpan di Kursus Saya' : 'Simpan Kursus Ini'">
                            <span class="material-symbols-outlined text-[18px]" :style="isBookmarked ? 'font-variation-settings: \'FILL\' 1;' : ''">bookmark</span>
                            <span x-text="isBookmarked ? 'Tersimpan' : 'Simpan'"></span>
                        </button>

                        @if($prevLesson)
                            <a href="{{ route('student.classroom.lesson', [$course->slug, $prevLesson->id]) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors shadow-xs">
                                <span class="material-symbols-outlined text-[18px]">skip_previous</span>
                                Sebelumnya
                            </a>
                        @endif

                        <!-- Toggle Completion Button (Alpine.js Ajax) -->
                        <button type="button" @click="toggleComplete()" 
                                :class="isCompleted ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold shadow-xs transition-colors duration-150">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;" 
                                  x-text="isCompleted ? 'check_circle' : 'radio_button_unchecked'"></span>
                            <span x-text="isCompleted ? 'Selesai ✓' : 'Tandai Selesai'"></span>
                        </button>

                        @if($nextLesson)
                            <a href="{{ route('student.classroom.lesson', [$course->slug, $nextLesson->id]) }}" 
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors shadow-xs">
                                Berikutnya
                                <span class="material-symbols-outlined text-[18px]">skip_next</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ==================== LESSON TABS: 5 FULL FEATURE TABS ==================== -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                <!-- Tab Navigation Bar -->
                <div class="flex border-b border-slate-200 bg-slate-50/75 px-6 gap-6 sm:gap-8 overflow-x-auto">
                    <!-- Tab 1: Overview -->
                    <button @click="activeTab = 'overview'" 
                            :class="activeTab === 'overview' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-900 font-medium'"
                            class="flex items-center gap-2 py-3.5 border-b-2 text-xs transition-colors -mb-px whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">article</span>
                        Ringkasan
                    </button>

                    <!-- Tab 2: Notes -->
                    <button @click="activeTab = 'notes'" 
                            :class="activeTab === 'notes' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-900 font-medium'"
                            class="flex items-center gap-2 py-3.5 border-b-2 text-xs transition-colors -mb-px whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">edit_note</span>
                        Catatan Pribadi
                        <span class="px-1.5 py-0.2 rounded-full bg-indigo-50 text-[10px] text-indigo-700 font-bold" x-text="notes.length"></span>
                    </button>

                    <!-- Tab 3: Discussion -->
                    <button @click="activeTab = 'discussion'" 
                            :class="activeTab === 'discussion' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-900 font-medium'"
                            class="flex items-center gap-2 py-3.5 border-b-2 text-xs transition-colors -mb-px whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">forum</span>
                        Tanya Jawab
                        <span class="px-1.5 py-0.2 rounded-full bg-indigo-50 text-[10px] text-indigo-700 font-bold" x-text="comments.length"></span>
                    </button>

                    <!-- Tab 4: Quiz -->
                    <button @click="activeTab = 'quiz'" 
                            :class="activeTab === 'quiz' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-900 font-medium'"
                            class="flex items-center gap-2 py-3.5 border-b-2 text-xs transition-colors -mb-px whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">quiz</span>
                        Kuis Checkpoint
                        <span class="px-1.5 py-0.2 rounded-full bg-indigo-50 text-[10px] text-indigo-700 font-bold" x-text="quizzes.length"></span>
                    </button>

                    <!-- Tab 5: Resources -->
                    <button @click="activeTab = 'resources'" 
                            :class="activeTab === 'resources' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-900 font-medium'"
                            class="flex items-center gap-2 py-3.5 border-b-2 text-xs transition-colors -mb-px whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">folder_open</span>
                        Sumber Daya
                        <span class="px-1.5 py-0.2 rounded-full bg-slate-100 text-[10px] text-slate-600 font-medium" x-text="resources.length"></span>
                    </button>
                </div>

                <div class="p-6">
                    <!-- ==================== PANE 1: OVERVIEW ==================== -->
                    <div x-show="activeTab === 'overview'" class="flex flex-col gap-5">
                        <h2 class="text-base font-bold text-slate-900">Rangkuman Materi Pelajaran</h2>
                        <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $currentLesson->description ?: 'Pelajari konsep inti dari video ini secara mendalam untuk memperkuat pemahaman arsitektur dan praktik pengembangan aplikasi berskala besar.' }}
                        </div>

                        <!-- Key Takeaways Bento Card -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 flex flex-col gap-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">verified</span>
                                POIN-POIN KUNCI PEMBELAJARAN
                            </span>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs text-slate-700">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0 mt-0.5">check_small</span>
                                    <span>Pahami alur eksekusi video dan integrasikan langsung ke kode nyata.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0 mt-0.5">check_small</span>
                                    <span>Terapkan kaidah performa tinggi dan pencegahan re-render berlebih.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0 mt-0.5">check_small</span>
                                    <span>Kelola mutasi data asinkron dan error boundary dengan aman.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0 mt-0.5">check_small</span>
                                    <span>Gunakan penanda timestamp untuk mengulang segmen video yang menantang.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- ==================== PANE 2: PERSONAL NOTES ==================== -->
                    <div x-show="activeTab === 'notes'" class="flex flex-col gap-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Catatan Pribadi Pembelajaran</h3>
                                <p class="text-xs text-slate-500">Catat poin penting dengan penanda menit video. Klik timestamp untuk melompat langsung ke segmen video.</p>
                            </div>
                        </div>

                        <!-- Add Note Form Box -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col gap-3">
                            <textarea x-model="newNoteContent"
                                      class="w-full rounded-xl border border-slate-200 bg-white text-xs p-3 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none resize-none" 
                                      placeholder="Tulis ringkasan atau pengingat penting mengenai video ini..." rows="2"></textarea>
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">timer</span>
                                    <input type="text" x-model="newNoteTimestampStr"
                                           placeholder="Timestamp (contoh: 04:30)" 
                                           class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-white font-mono w-40 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"/>
                                </div>
                                <button type="button" @click="saveNote()" :disabled="isSavingNote || !newNoteContent.trim()"
                                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-xs">
                                    <span class="material-symbols-outlined text-[16px]">save</span>
                                    <span x-text="isSavingNote ? 'Menyimpan...' : 'Simpan Catatan'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- List of Notes -->
                        <div class="flex flex-col gap-3">
                            <template x-for="note in notes" :key="note.id">
                                <div class="flex items-start justify-between gap-3 p-3.5 rounded-xl bg-white border border-slate-200 hover:border-slate-300 transition-colors shadow-xs">
                                    <div class="flex items-start gap-3 flex-1">
                                        <button type="button" @click="seekVideo(note.timestamp_seconds)"
                                                title="Lompat ke menit ini di pemutar video"
                                                class="px-2 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white transition-colors font-mono text-xs font-bold flex items-center gap-1 shrink-0 border border-indigo-100">
                                            <span class="material-symbols-outlined text-[14px]">play_circle</span>
                                            <span x-text="note.formatted_timestamp"></span>
                                        </button>
                                        <div class="flex flex-col gap-1 flex-1">
                                            <p class="text-xs text-slate-900 leading-relaxed whitespace-pre-line" x-text="note.content"></p>
                                            <span class="text-[10px] text-slate-400" x-text="note.created_at"></span>
                                        </div>
                                    </div>
                                    <button type="button" @click="deleteNote(note.id)" title="Hapus Catatan"
                                            class="text-slate-400 hover:text-rose-600 transition-colors p-1">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                            </template>

                            <div x-show="notes.length === 0" class="py-10 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <span class="material-symbols-outlined text-[36px] text-slate-300 mb-1">note_stack</span>
                                <p class="text-xs font-medium text-slate-600">Belum ada catatan pribadi untuk pelajaran ini.</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Tulis catatan di atas untuk membantu review materi penting saat belajar!</p>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== PANE 3: DISCUSSION (THREADED & PERSISTENT) ==================== -->
                    <div x-show="activeTab === 'discussion'" class="flex flex-col gap-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Tanya Jawab & Diskusi Komunitas</h3>
                                <p class="text-xs text-slate-500">Diskusikan materi, ajukan pertanyaan seputar video, dan berikan balasan secara langsung.</p>
                            </div>
                        </div>

                        <!-- Top Level Comment Box -->
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 bg-slate-100 ring-1 ring-slate-200">
                                <img class="w-full h-full object-cover" src="{{ $student->avatar_url }}" alt="Avatar"/>
                            </div>
                            <div class="flex-1 flex flex-col gap-2">
                                <textarea x-model="newCommentContent"
                                          class="w-full rounded-xl border border-slate-200 bg-white text-xs p-3 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none resize-none" 
                                          placeholder="Tulis pertanyaan atau bagikan pemahaman Anda tentang materi ini..." rows="2"></textarea>
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <input type="text" x-model="newCommentTimestamp"
                                           placeholder="Opsi waktu menit (misal: 14:12)" 
                                           class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-white font-mono w-48 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"/>
                                    <button type="button" @click="submitComment()" :disabled="isSubmittingComment || !newCommentContent.trim()"
                                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-xs">
                                        <span class="material-symbols-outlined text-[16px]">send</span>
                                        <span x-text="isSubmittingComment ? 'Mengirim...' : 'Kirim Komentar'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Discussion Comments List with Replies -->
                        <div class="flex flex-col gap-4">
                            <template x-for="comment in comments" :key="comment.id">
                                <div class="flex flex-col p-4 rounded-xl bg-slate-50 border border-slate-200 gap-3">
                                    <!-- Main Comment -->
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 bg-slate-100 ring-1 ring-slate-200">
                                            <img class="w-full h-full object-cover" :src="comment.user_avatar" :alt="comment.user_name"/>
                                        </div>
                                        <div class="flex flex-col gap-1 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-900" x-text="comment.user_name"></span>
                                                <span class="text-[11px] text-slate-400" x-text="'• ' + comment.created_at"></span>
                                                <span class="px-1.5 py-0.2 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-mono border border-indigo-100" x-text="comment.timestamp_str"></span>
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed" x-text="comment.content"></p>

                                            <!-- Reply & Like Buttons -->
                                            <div class="flex items-center gap-3 pt-1 text-xs">
                                                <button type="button" @click="toggleLike(comment)" 
                                                        class="flex items-center gap-1 font-semibold transition-colors"
                                                        :class="comment.is_liked ? 'text-rose-600' : 'text-slate-400 hover:text-indigo-600'">
                                                    <span class="material-symbols-outlined text-[15px]" 
                                                          :style="comment.is_liked ? 'font-variation-settings: \'FILL\' 1;' : ''">favorite</span>
                                                    <span x-text="comment.likes_count"></span>
                                                </button>
                                                <button type="button" @click="comment.show_reply_box = !comment.show_reply_box" 
                                                        class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">reply</span>
                                                    <span>Balas</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reply Box for this Comment -->
                                    <div x-show="comment.show_reply_box" class="ml-11 pt-2 flex flex-col gap-2">
                                        <textarea x-model="comment.reply_content"
                                                  class="w-full rounded-xl border border-slate-200 bg-white text-xs p-2.5 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none resize-none" 
                                                  placeholder="Tulis balasan untuk komentar ini..." rows="2"></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="comment.show_reply_box = false" 
                                                    class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-700 rounded-lg">Batal</button>
                                            <button type="button" @click="submitComment(comment.id, comment.reply_content)"
                                                    class="px-3.5 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 shadow-xs">Kirim Balasan</button>
                                        </div>
                                    </div>

                                    <!-- Nested Replies List -->
                                    <template x-if="comment.replies && comment.replies.length > 0">
                                        <div class="ml-11 flex flex-col gap-2.5 pl-3 border-l-2 border-slate-200 pt-1">
                                            <template x-for="reply in comment.replies" :key="reply.id">
                                                <div class="flex items-start gap-2.5 bg-white p-2.5 rounded-xl border border-slate-200">
                                                    <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 bg-slate-100">
                                                        <img class="w-full h-full object-cover" :src="reply.user_avatar" :alt="reply.user_name"/>
                                                    </div>
                                                    <div class="flex flex-col gap-0.5 flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs font-bold text-slate-900" x-text="reply.user_name"></span>
                                                            <span class="text-[10px] text-slate-400" x-text="'• ' + reply.created_at"></span>
                                                        </div>
                                                        <p class="text-xs text-slate-600" x-text="reply.content"></p>
                                                        <div class="flex items-center gap-2 pt-1 text-xs">
                                                            <button type="button" @click="toggleLike(reply)" 
                                                                    class="flex items-center gap-1 font-semibold transition-colors"
                                                                    :class="reply.is_liked ? 'text-rose-600' : 'text-slate-400 hover:text-indigo-600'">
                                                                <span class="material-symbols-outlined text-[13px]" 
                                                                      :style="reply.is_liked ? 'font-variation-settings: \'FILL\' 1;' : ''">favorite</span>
                                                                <span x-text="reply.likes_count"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- ==================== PANE 4: INTERACTIVE QUIZ (PERSISTENT ATTEMPTS) ==================== -->
                    <div x-show="activeTab === 'quiz'" class="flex flex-col gap-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Kuis Checkpoint Pemahaman</h3>
                                <p class="text-xs text-slate-500">Uji pemahaman Anda terhadap konsep materi video ini. Nilai Anda akan disimpan permanen ke database.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <template x-if="quizAttemptScore !== null">
                                    <span :class="quizAttemptPassed ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'"
                                          class="px-3 py-1 rounded-full text-xs font-bold border flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px]" x-text="quizAttemptPassed ? 'verified' : 'error'"></span>
                                        <span x-text="'Nilai: ' + quizAttemptScore + ' / 100'"></span>
                                        <span x-text="quizAttemptPassed ? '(Lulus)' : '(Belum Lulus)'"></span>
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Quiz Questions Loop -->
                        <div class="flex flex-col gap-6">
                            <template x-for="(quiz, qIdx) in quizzes" :key="quiz.id">
                                <div class="p-5 rounded-xl border border-slate-200 bg-white flex flex-col gap-4 shadow-xs">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-start gap-2.5">
                                            <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-mono text-xs font-bold shrink-0 mt-0.5" 
                                                  x-text="qIdx + 1"></span>
                                            <h4 class="text-sm font-bold text-slate-900 leading-snug" x-text="quiz.question"></h4>
                                        </div>
                                        <span x-show="quiz.is_answered" 
                                              :class="quiz.selected_option === quiz.correct_answer_index ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'"
                                              class="px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0" 
                                              x-text="quiz.selected_option === quiz.correct_answer_index ? 'Jawaban Benar ✓' : 'Kurang Tepat'">
                                        </span>
                                    </div>

                                    <!-- Options List -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <template x-for="(opt, optIdx) in quiz.options" :key="optIdx">
                                            <button type="button" @click="selectQuizAnswer(qIdx, optIdx)"
                                                    :disabled="quizAttemptPassed === true"
                                                    :class="{
                                                        'bg-emerald-50 border-emerald-500 text-emerald-900 font-semibold ring-1 ring-emerald-500': quiz.is_answered && optIdx === quiz.correct_answer_index,
                                                        'bg-rose-50 border-rose-500 text-rose-900 line-through': quiz.is_answered && quiz.selected_option === optIdx && optIdx !== quiz.correct_answer_index,
                                                        'bg-indigo-50 border-indigo-600 text-indigo-700 font-semibold': !quiz.is_answered && quiz.selected_option === optIdx,
                                                        'bg-white border-slate-200 hover:bg-slate-50 text-slate-900': !quiz.is_answered && quiz.selected_option !== optIdx,
                                                        'bg-slate-50 border-slate-200 text-slate-400 opacity-75': quiz.is_answered && optIdx !== quiz.correct_answer_index && quiz.selected_option !== optIdx
                                                    }"
                                                    class="p-3 rounded-xl border text-xs text-left transition-all flex items-center justify-between gap-2 shadow-xs">
                                                <span x-text="opt"></span>
                                                <span x-show="quiz.is_answered && optIdx === quiz.correct_answer_index" class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                                                <span x-show="quiz.is_answered && quiz.selected_option === optIdx && optIdx !== quiz.correct_answer_index" class="material-symbols-outlined text-[16px] text-rose-600">cancel</span>
                                            </button>
                                        </template>
                                    </div>

                                    <!-- Explanation Box -->
                                    <div x-show="quiz.is_answered" 
                                         class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 flex items-start gap-2">
                                        <span class="material-symbols-outlined text-[18px] text-indigo-600 shrink-0 mt-0.5">info</span>
                                        <div>
                                            <span class="font-bold text-indigo-700">Penjelasan: </span>
                                            <span x-text="quiz.explanation"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="quizzes.length > 0" class="flex justify-end pt-2">
                                <button type="button" @click="submitAllQuizAnswers()" 
                                        :disabled="isSubmittingQuiz"
                                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-all flex items-center gap-2 shadow-xs disabled:opacity-50">
                                    <span class="material-symbols-outlined text-[18px]">send</span>
                                    <span x-text="isSubmittingQuiz ? 'Memeriksa...' : (quizAttemptScore !== null ? 'Kirim Ulang Kuis' : 'Kirim Jawaban Kuis')"></span>
                                </button>
                            </div>

                            <div x-show="quizzes.length === 0" class="py-10 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <span class="material-symbols-outlined text-[36px] text-slate-300 mb-1">quiz</span>
                                <p class="text-xs font-medium text-slate-600">Pelajaran ini difokuskan pada praktik langsung tanpa kuis pengujian.</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Lanjutkan ke materi berikutnya setelah Anda mempraktikkan kode di video!</p>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== PANE 5: DYNAMIC RESOURCES ==================== -->
                    <div x-show="activeTab === 'resources'" class="flex flex-col gap-4">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Materi & Berkas Sumber Pembelajaran</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <template x-for="res in resources" :key="res.id">
                                <a class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 transition-colors group shadow-xs" 
                                   :href="res.url" target="_blank" rel="noopener noreferrer">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px]" x-text="res.icon"></span>
                                    </div>
                                    <div class="flex flex-col truncate">
                                        <span class="text-xs font-semibold text-slate-900 truncate group-hover:text-indigo-600" x-text="res.title"></span>
                                        <span class="text-[11px] text-slate-400 uppercase" x-text="res.type + ' • Buka Tautan'"></span>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <div x-show="resources.length === 0" class="py-10 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <span class="material-symbols-outlined text-[36px] text-slate-300 mb-1">folder_off</span>
                            <p class="text-xs font-medium text-slate-600">Belum ada berkas sumber daya tambahan untuk materi ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== RIGHT COLUMN (30% - 4/12 cols) ==================== -->
        <!-- Responsive Course Curriculum: Stacks on mobile, sticky on desktop -->
        <aside class="lg:col-span-4 lg:sticky lg:top-20 flex flex-col gap-4">
            <!-- Overall Progress Summary Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-indigo-700 tracking-wide uppercase">SILABUS KURSUS</span>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full" 
                          x-text="progress + '% Selesai'">
                        {{ $progressPercentage }}% Selesai
                    </span>
                </div>
                <div class="flex flex-col gap-1.5">
                    <div class="flex justify-between items-baseline">
                        <h2 class="text-sm font-bold text-slate-900">{{ $course->title }}</h2>
                        <span class="font-mono text-xs text-slate-400" 
                              x-text="completedCount + '/' + totalCount + ' Pelajaran'">
                            {{ $completedLessonsCount }}/{{ $totalLessonsCount }} Pelajaran
                        </span>
                    </div>
                    <!-- Progress Track -->
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" 
                             :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>

                <!-- Graduation Certificate Trigger / Status Requirements Card -->
                <div x-show="certificateUrl" 
                     x-cloak 
                     @if(! $certificate) style="display: none;" @endif 
                     class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <div class="flex items-center justify-between text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100 font-medium">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600" style="font-variation-settings: 'FILL' 1;">verified</span>
                            <span>Syarat Kelulusan Terpenuhi</span>
                        </span>
                        <span class="font-bold uppercase tracking-wider text-[10px] bg-emerald-200/60 text-emerald-800 px-1.5 py-0.5 rounded">Lulus</span>
                    </div>
                    <a :href="certificateUrl" 
                       class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white text-xs font-semibold flex items-center justify-center gap-2 shadow-xs transition-all">
                        <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                        <span>Lihat & Cetak Sertifikat Kelulusan</span>
                    </a>
                </div>

                <div x-show="!certificateUrl" 
                     x-cloak 
                     @if($certificate) style="display: none;" @endif 
                     class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">lock</span>
                            <span>Syarat Sertifikat</span>
                        </span>
                        <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">Terkunci</span>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-2.5 border border-slate-100 flex flex-col gap-2 text-[11px]">
                        <!-- Syarat 1: Video Ditonton Selesai -->
                        <div class="flex items-center justify-between" :class="completedCount >= totalCount ? 'text-emerald-700' : 'text-slate-600'">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px]" 
                                      :class="completedCount >= totalCount ? 'text-emerald-600' : 'text-slate-400'"
                                      x-text="completedCount >= totalCount ? 'check_circle' : 'radio_button_unchecked'">{{ $completedLessonsCount >= $totalLessonsCount ? 'check_circle' : 'radio_button_unchecked' }}</span>
                                <span>Tonton Semua Video</span>
                            </span>
                            <span class="font-mono text-[10px] font-semibold" 
                                  :class="completedCount >= totalCount ? 'text-emerald-600' : 'text-slate-500'"
                                  x-text="completedCount + '/' + totalCount">{{ $completedLessonsCount }}/{{ $totalLessonsCount }}</span>
                        </div>

                        @if($totalQuizzesCount > 0)
                            <!-- Syarat 2: Kuis Diselesaikan & Lulus -->
                            <div class="flex items-center justify-between" :class="hasPassedQuizzes ? 'text-emerald-700' : 'text-slate-600'">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px]" 
                                          :class="hasPassedQuizzes ? 'text-emerald-600' : 'text-slate-400'"
                                          x-text="hasPassedQuizzes ? 'check_circle' : 'radio_button_unchecked'">{{ $hasPassedAllQuizzes ? 'check_circle' : 'radio_button_unchecked' }}</span>
                                    <span>Kerjakan & Lulus Kuis</span>
                                </span>
                                <span class="font-mono text-[10px] font-semibold" 
                                      :class="hasPassedQuizzes ? 'text-emerald-600' : 'text-amber-600'"
                                      x-text="passedQuizzes + '/' + totalQuizzes">{{ $passedQuizzesCount }}/{{ $totalQuizzesCount }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Accordion Curriculum Module List -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs flex flex-col divide-y divide-slate-100">
                @foreach($modules as $moduleTitle => $lessonsInModule)
                    @php
                        $isCurrentModule = $lessonsInModule->contains('id', $currentLesson->id);
                        $moduleCompletedCount = $lessonsInModule->filter(fn($l) => $l->isCompletedBy($student))->count();
                        $moduleTotalCount = $lessonsInModule->count();
                    @endphp
                    <div class="flex flex-col" x-data="{ expanded: {{ $isCurrentModule ? 'true' : 'false' }} }">
                        <!-- Module Header Button -->
                        <button type="button" @click="expanded = !expanded" 
                                class="w-full p-4 flex items-center justify-between text-left transition-colors {{ $isCurrentModule ? 'bg-slate-50 border-l-4 border-indigo-600' : 'bg-white hover:bg-slate-50/75' }}">
                            <div class="flex items-center gap-3">
                                @if($moduleCompletedCount === $moduleTotalCount && $moduleTotalCount > 0)
                                    <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100">
                                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">check</span>
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full {{ $isCurrentModule ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }} flex items-center justify-center font-mono text-xs font-bold shrink-0">
                                        {{ $loop->iteration }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-xs font-bold {{ $isCurrentModule ? 'text-indigo-700' : 'text-slate-900' }}">
                                        {{ $moduleTitle }}
                                    </h3>
                                    <p class="text-[11px] text-slate-400">{{ $moduleCompletedCount }}/{{ $moduleTotalCount }} Selesai</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-[20px] {{ $isCurrentModule ? 'text-indigo-600' : 'text-slate-400' }}" 
                                  x-text="expanded ? 'expand_less' : 'expand_more'"></span>
                        </button>

                        <!-- Lessons List Inside Module -->
                        <div x-show="expanded" class="flex flex-col divide-y divide-slate-100 bg-white">
                            @foreach($lessonsInModule as $les)
                                @php
                                    $isThisActive = $les->id === $currentLesson->id;
                                    $isThisDone = $les->isCompletedBy($student);
                                @endphp

                                @if($isThisActive)
                                    <!-- ACTIVE CURRENT PLAYING LESSON -->
                                    <div class="p-3.5 pl-5 border-l-4 border-indigo-600 bg-indigo-50/40 flex items-center justify-between">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-5 h-5 rounded flex items-end justify-center gap-0.5 pb-0.5 shrink-0 bg-indigo-100 text-indigo-700">
                                                <div class="w-1 bg-indigo-600 rounded-t eq-bar-1"></div>
                                                <div class="w-1 bg-indigo-600 rounded-t eq-bar-2"></div>
                                                <div class="w-1 bg-indigo-600 rounded-t eq-bar-3"></div>
                                            </div>
                                            <div class="flex flex-col truncate">
                                                <span class="text-xs font-bold text-indigo-700 truncate">
                                                    {{ $les->title }}
                                                </span>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-mono text-[11px] font-semibold text-indigo-600">Sedang Diputar</span>
                                                    <span class="text-slate-300">•</span>
                                                    <span class="font-mono text-[11px] text-slate-400">{{ $les->duration_formatted }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 py-0.5 rounded-full bg-indigo-600 text-white text-[10px] font-semibold shrink-0">
                                            Aktif
                                        </div>
                                    </div>
                                @else
                                    <!-- INACTIVE / COMPLETED / UPCOMING LESSON -->
                                    <a href="{{ route('student.classroom.lesson', [$course->slug, $les->id]) }}" 
                                       class="p-3.5 pl-6 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            @if($isThisDone)
                                                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">check</span>
                                                </div>
                                            @else
                                                <div class="w-5 h-5 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 shrink-0">
                                                    <span class="material-symbols-outlined text-[13px]">play_arrow</span>
                                                </div>
                                            @endif
                                            <div class="flex flex-col truncate">
                                                <span class="text-xs {{ $isThisDone ? 'text-slate-500' : 'text-slate-800' }} group-hover:text-indigo-600 truncate font-medium">
                                                    {{ $les->title }}
                                                </span>
                                                <span class="font-mono text-[11px] text-slate-400">
                                                    {{ $les->duration_formatted }} {{ $isThisDone ? '• Selesai' : '' }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-[18px] text-slate-400 group-hover:text-indigo-600 shrink-0">
                                            {{ $isThisDone ? 'replay' : 'play_circle' }}
                                        </span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>
    </div>

    <!-- ==================== GRADUATION CELEBRATION MODAL ==================== -->
    <div x-show="showGraduationModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="showGraduationModal = false"
             x-show="showGraduationModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 p-6 sm:p-8 text-center flex flex-col items-center gap-4">
            
            <div class="relative flex items-center justify-center">
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                    <span class="material-symbols-outlined text-[42px]" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                </div>
                <div class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-amber-400 text-amber-950 flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">grade</span>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Selamat! Kursus Selesai</span>
                <h3 class="text-lg font-bold text-slate-900">Sertifikat Kelulusan Terbuka</h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                    Kamu telah menyelesaikan semua video dan menuntaskan kuis evaluasi pada kursus <strong>{{ $course->title }}</strong>. Sertifikat resmi kelulusanmu telah diterbitkan!
                </p>
            </div>

            <div class="w-full flex flex-col sm:flex-row items-center gap-2.5 pt-2">
                <a :href="certificateUrl"
                   class="w-full sm:flex-1 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span>Lihat Sertifikat</span>
                </a>
                <button type="button" 
                        @click="showGraduationModal = false"
                        class="w-full sm:w-auto py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Nanti
                </button>
            </div>
        </div>
    </div>
</main>

<!-- ==================== YOUTUBE IFRAME API SCRIPT & PLAYER RESUME / AUTO-COMPLETE CONTROLLER ==================== -->
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    window.ytPlayer = null;
    let syncInterval = null;

    function syncVideoProgress(seconds, markCompleted = false, callback = null) {
        fetch('{{ route('student.lesson.progress', $currentLesson->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                seconds: Math.floor(seconds) || 0,
                mark_completed: markCompleted ? 1 : 0
            })
        })
        .then(res => res.json())
        .then(data => {
            if (callback) callback(data);
        })
        .catch(() => {});
    }

    function onYouTubeIframeAPIReady() {
        window.ytPlayer = new YT.Player('youtube-player', {
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        const startSec = {{ (int) $lastWatchedSecond }};
        if (startSec > 3) {
            try {
                event.target.seekTo(startSec, true);
            } catch (e) {}
        }

        if (syncInterval) clearInterval(syncInterval);
        syncInterval = setInterval(() => {
            if (window.ytPlayer && typeof window.ytPlayer.getCurrentTime === 'function' && typeof window.ytPlayer.getPlayerState === 'function') {
                if (window.ytPlayer.getPlayerState() === YT.PlayerState.PLAYING) {
                    const currentSec = window.ytPlayer.getCurrentTime();
                    syncVideoProgress(currentSec, false);
                }
            }
        }, 10000);
    }

    function onPlayerStateChange(event) {
        // Event state 0 is YT.PlayerState.ENDED
        if (event.data === 0) {
            const currentSec = window.ytPlayer && typeof window.ytPlayer.getCurrentTime === 'function' 
                ? window.ytPlayer.getCurrentTime() 
                : 0;

            syncVideoProgress(currentSec, true, (data) => {
                window.dispatchEvent(new CustomEvent('lesson-video-ended', { detail: data }));
            });
        }
    }
</script>
@endsection
