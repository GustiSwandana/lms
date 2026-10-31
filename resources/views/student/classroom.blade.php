@extends('layouts.app')

@section('content')
<!-- ==================== TOP NAVIGATION BAR (LIGHT MODE) ==================== -->
<header class="flex justify-between items-center w-full px-4 sm:px-6 h-16 border-b border-slate-200 sticky top-0 z-50 bg-white/95 backdrop-blur-md">
    <!-- Left Section: Back to Dashboard & Breadcrumbs -->
    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
        <a class="inline-flex items-center justify-center gap-2 min-h-11 px-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 shadow-2xs transition-colors" 
           href="{{ route('student.dashboard') }}" title="Kembali ke Dasbor">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span class="text-xs font-semibold hidden sm:inline">Dasbor</span>
        </a>

        <div class="h-5 w-px bg-slate-200 mx-0.5 hidden md:block"></div>

        <!-- Compact Breadcrumb Trail -->
        <nav aria-label="Breadcrumb" class="hidden sm:flex items-center gap-1.5 text-slate-500 text-xs font-medium truncate">
            <a class="hover:text-emerald-700 transition-colors" href="{{ route('student.dashboard') }}">Kursus</a>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <a class="hover:text-emerald-700 transition-colors max-w-[160px] truncate" href="{{ route('student.classroom', $course->slug) }}">{{ $course->title }}</a>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <span class="text-slate-900 font-semibold truncate max-w-[180px]">{{ $currentLesson->module_title ?: 'Modul Materi' }}</span>
        </nav>
    </div>

    <!-- Center/Right Navigation Links & Actions -->
    <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
        @if($certificate)
            <a href="{{ route('student.certificate', $certificate->certificate_code) }}" 
               class="inline-flex items-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl bg-amber-50 border border-amber-300 hover:bg-amber-100 text-amber-800 text-xs font-bold transition-all"
               title="Kursus Telah Selesai 100%! Buka Sertifikat Resmi Anda">
                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                <span class="hidden sm:inline">Sertifikat Resmi</span>
            </a>
        @endif

        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center justify-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 shadow-2xs text-xs font-semibold transition-all">
            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
            <span class="hidden md:inline">Portal Admin</span>
        </a>

        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- Instructor/Student Account Menu -->
        <div class="flex items-center gap-2.5 pl-1">
            <div class="w-9 h-9 rounded-full ring-1 ring-slate-200 overflow-hidden bg-slate-100 shrink-0">
                <img class="w-full h-full object-cover" 
                     src="{{ $student->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}" 
                     alt="Foto Siswa"/>
            </div>
            <div class="hidden lg:flex flex-col text-left leading-tight">
                <span class="text-xs font-semibold text-slate-900">{{ $student->name }}</span>
                <span class="text-[11px] text-slate-500 font-mono">{{ $student->school_name ?: 'Siswa SSK' }}</span>
            </div>
        </div>
    </div>
</header>

<!-- ==================== MAIN LEARNING VIEW (SPLIT SCREEN) ==================== -->
<main class="flex-1 w-full max-w-[1720px] mx-auto px-3 sm:px-6 lg:px-8 py-5 pb-24 md:pb-10"
      @lesson-video-ended.window="isCompleted = true; if($event.detail) { if($event.detail.progress !== undefined) progress = $event.detail.progress; if($event.detail.completed_count !== undefined) completedCount = $event.detail.completed_count; if($event.detail.passed_quizzes_count !== undefined) passedQuizzes = $event.detail.passed_quizzes_count; if($event.detail.has_passed_all_quizzes !== undefined) hasPassedQuizzes = $event.detail.has_passed_all_quizzes; if($event.detail.certificate) { certificateUrl = $event.detail.certificate.url; showGraduationModal = true; } }"
      x-data="{ 
          activeRightTab: 'syllabus',
          activeLeftTab: 'overview',
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

          captureCurrentTimestamp() {
              if (window.ytPlayer && typeof window.ytPlayer.getCurrentTime === 'function') {
                  const sec = Math.floor(window.ytPlayer.getCurrentTime());
                  const m = Math.floor(sec / 60);
                  const s = sec % 60;
                  this.newNoteTimestampStr = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
              }
          },

          retryQuiz() {
              this.quizAttemptScore = null;
              this.quizAttemptPassed = null;
              for (const q of this.quizzes) {
                  q.is_answered = false;
                  q.selected_option = null;
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

    <!-- ==================== AUTO-NEXT COUNTDOWN TOAST ==================== -->
    <div x-show="showAutoNextToast" 
         x-cloak
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="fixed bottom-6 right-6 z-50 bg-white text-slate-900 p-4 rounded-2xl shadow-xl border border-emerald-300 max-w-sm flex flex-col gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">task_alt</span>
            </div>
            <div class="text-left">
                <h4 class="text-xs font-bold text-slate-900">Materi Selesai!</h4>
                <p class="text-[11px] text-slate-600">
                    Materi berikutnya diputar dalam <span class="text-emerald-700 font-mono font-bold" x-text="autoNextCountdown + ' dtk'"></span>.
                </p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" @click="cancelAutoNext()" class="min-h-11 px-3 py-1.5 rounded-xl text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                Batal
            </button>
            <button type="button" @click="proceedAutoNextNow()" class="min-h-11 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                <span>Lanjut Sekarang</span>
                <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- ==================== LEFT COLUMN (70% - 8/12 cols) ==================== -->
        <section class="lg:col-span-8 flex flex-col gap-5 min-w-0">
            <!-- Embedded 16:9 Video Player (Sticky on Mobile < md, normal on desktop) -->
            <div class="sticky top-16 md:static z-30 -mx-3 sm:mx-0">
                <div class="relative w-full aspect-video bg-slate-950 sm:rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                    <iframe id="youtube-player"
                            class="w-full h-full aspect-video absolute inset-0 z-10 border-0" 
                            src="https://www.youtube-nocookie.com/embed/{{ $currentLesson->youtube_video_id }}?autoplay=1&enablejsapi=1&rel=0&modestbranding=1" 
                            title="{{ $currentLesson->title }}" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- Live Watch Completion Tracker & Video Action Toolbar -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col gap-3.5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full" :class="isCompleted ? 'bg-emerald-500 ring-2 ring-emerald-200' : 'bg-amber-400'"></span>
                        <span class="text-xs font-semibold text-slate-700" x-text="isCompleted ? 'Materi ini sudah kamu tonton tuntas' : 'Tonton minimal 90% durasi untuk menyelesaikan materi'"></span>
                    </div>

                    <!-- Fast Scroll to Quiz CTA if quizzes exist -->
                    <template x-if="quizzes.length > 0">
                        <button type="button" 
                                @click="document.getElementById('checkpoint-quiz-section')?.scrollIntoView({ behavior: 'smooth' })"
                                :class="isCompleted ? 'bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-xs' : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-semibold'"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs transition-all shrink-0 min-h-11">
                            <span class="material-symbols-outlined text-[16px]">quiz</span>
                            <span>Buka Kuis Checkpoint (<span class="font-mono tabular-nums" x-text="quizzes.length"></span> Soal)</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_downward</span>
                        </button>
                    </template>
                </div>

                <!-- Linear Progress Bar -->
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-600 transition-all duration-300 rounded-full"
                         :style="'width: ' + (isCompleted ? '100%' : '15%')"></div>
                </div>

                <!-- Navigation & Action Buttons (Min 44px Touch Targets) -->
                <div class="flex flex-wrap items-center justify-between gap-2.5 pt-1">
                    <div class="flex items-center gap-2">
                        @if($prevLesson)
                            <a href="{{ route('student.classroom.lesson', [$course->slug, $prevLesson->id]) }}" 
                               class="inline-flex items-center justify-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition-colors">
                                <span class="material-symbols-outlined text-[18px]">skip_previous</span>
                                <span>Sebelumnya</span>
                            </a>
                        @endif

                        <!-- Toggle Completion Button -->
                        <button type="button" @click="toggleComplete()" 
                                :class="isCompleted ? 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100' : 'bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200'"
                                class="inline-flex items-center justify-center gap-2 min-h-11 px-4 py-2 rounded-xl border text-xs font-semibold transition-all shadow-2xs">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;" 
                                  x-text="isCompleted ? 'check_circle' : 'radio_button_unchecked'"></span>
                            <span x-text="isCompleted ? 'Selesai ✓' : 'Tandai Selesai'"></span>
                        </button>

                        @if($nextLesson)
                            <a href="{{ route('student.classroom.lesson', [$course->slug, $nextLesson->id]) }}" 
                                class="inline-flex items-center justify-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition-colors">
                                <span>Berikutnya</span>
                                <span class="material-symbols-outlined text-[18px]">skip_next</span>
                            </a>
                        @endif
                    </div>

                    <!-- Bookmark Action -->
                    <button type="button" @click="toggleCourseBookmark()" 
                            :class="isBookmarked ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                            class="inline-flex items-center justify-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl border text-xs font-semibold shadow-2xs transition-colors">
                        <span class="material-symbols-outlined text-[18px]" :style="isBookmarked ? 'font-variation-settings: \'FILL\' 1;' : ''">bookmark</span>
                        <span x-text="isBookmarked ? 'Tersimpan' : 'Simpan Kursus'"></span>
                    </button>
                </div>
            </div>

            <!-- ==================== LESSON DETAILS CARD ==================== -->
            <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col gap-4 text-left">
                <!-- Pillar Badges & Metadata -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold">
                        <span class="material-symbols-outlined text-[14px]">school</span>
                        {{ $course->category->name ?? 'SSK BKKBN' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                        <span class="material-symbols-outlined text-[14px]">apartment</span>
                        {{ $course->school_name ?: 'BKKBN Perwakilan Sulut' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-xs font-medium">
                        {{ $currentLesson->module_title }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-slate-500 font-mono tabular-nums ml-auto">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        {{ $currentLesson->duration_formatted }}
                    </span>
                </div>

                <!-- Main Lesson Title -->
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-snug">
                    {{ $currentLesson->title }}
                </h1>

                <!-- Instructor Avatar & Bio Line -->
                <div class="flex items-center gap-3 pt-1 border-t border-slate-100">
                    <div class="w-9 h-9 rounded-full overflow-hidden ring-1 ring-slate-200 bg-slate-100 shrink-0">
                        <img class="w-full h-full object-cover" 
                             src="{{ $course->instructor_avatar ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $course->instructor_name }}"/>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-xs font-bold text-slate-900">{{ $course->instructor_name }}</span>
                        <span class="text-[11px] text-slate-500">{{ $course->instructor_title }}</span>
                    </div>
                </div>

                <!-- Lesson Description -->
                <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line text-left pt-2 border-t border-slate-100">
                    {{ $currentLesson->description ?: 'Pelajari materi ini dengan cermat untuk memahami integrasi kependudukan dan keberlanjutan lingkungan sesuai modul SSK BKKBN Sulut.' }}
                </div>

                <!-- Downloadable Resources Section (If Available) -->
                <div x-show="resources.length > 0" class="pt-3 border-t border-slate-100 flex flex-col gap-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-emerald-700">attach_file</span>
                        <span>Berkas & Materi Pendukung</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <template x-for="res in resources" :key="res.id">
                            <a class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500/50 bg-slate-50/60 hover:bg-emerald-50/30 transition-all min-h-12" 
                               :href="res.url" target="_blank" rel="noopener noreferrer">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]" x-text="res.icon"></span>
                                </div>
                                <div class="flex flex-col truncate text-left">
                                    <span class="text-xs font-semibold text-slate-900 truncate" x-text="res.title"></span>
                                    <span class="text-[10px] text-slate-500 uppercase font-mono" x-text="res.type + ' • Buka Tautan'"></span>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ==================== CHECKPOINT QUIZ SECTION (FOCUSED SINGLE-COLUMN) ==================== -->
            <div id="checkpoint-quiz-section" x-show="quizzes.length > 0" class="p-5 sm:p-7 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col gap-5 text-left">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-4">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">quiz</span>
                            Kuis Checkpoint Pemahaman
                        </span>
                        <h2 class="text-base font-bold text-slate-900 mt-0.5">Uji Pemahaman Materi</h2>
                        <p class="text-xs text-slate-600 mt-1">Selesaikan kuis dengan nilai minimal <span class="font-mono tabular-nums font-semibold text-emerald-700">70%</span> untuk melengkapi syarat kelulusan kursus.</p>
                    </div>

                    <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                        <template x-if="quizAttemptScore !== null">
                            <div class="flex items-center gap-2">
                                <span :class="quizAttemptPassed ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-amber-50 text-amber-800 border-amber-300'"
                                      class="px-3.5 py-1.5 rounded-xl text-xs font-bold border flex items-center gap-1.5 font-mono tabular-nums">
                                    <span class="material-symbols-outlined text-[16px]" x-text="quizAttemptPassed ? 'verified' : 'info'"></span>
                                    <span x-text="'Nilai Kamu: ' + quizAttemptScore + ' / 100'"></span>
                                    <span x-text="quizAttemptPassed ? '(Lulus ✓)' : '(Belum Lulus)'"></span>
                                </span>
                                <button x-show="!quizAttemptPassed" type="button" @click="retryQuiz()"
                                        class="min-h-11 px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                                    <span>Ulangi Kuis</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Encouraging Feedback Callout if < 70% -->
                <div x-show="quizAttemptScore !== null && !quizAttemptPassed" 
                     class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start justify-between gap-3 shadow-2xs">
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-[20px] text-amber-600 shrink-0 mt-0.5">sentiment_satisfied</span>
                        <div>
                            <p class="font-bold text-amber-900">Sedikit lagi lulus passing grade 70%!</p>
                            <p class="text-[11px] text-amber-800 mt-0.5">Kamu bisa tonton ulang materi dan coba kuis lagi tanpa batasan.</p>
                        </div>
                    </div>
                    <button type="button" @click="retryQuiz()" 
                            class="min-h-11 px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shrink-0 transition-colors shadow-xs">
                        Ulangi Kuis
                    </button>
                </div>

                <!-- Congratulatory Callout if Passed -->
                <div x-show="quizAttemptPassed" 
                     class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-start gap-2.5 shadow-2xs">
                    <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div>
                        <p class="font-bold text-emerald-900">Hebat, kamu berhasil lulus kuis ini!</p>
                        <p class="text-[11px] text-emerald-800 mt-0.5">Jawabanmu sudah melampaui batas nilai kelulusan. Lanjut ke materi berikutnya!</p>
                    </div>
                </div>

                <!-- Quiz Questions List -->
                <div class="flex flex-col gap-6 max-w-2xl mx-auto w-full">
                    <template x-for="(quiz, qIdx) in quizzes" :key="quiz.id">
                        <div class="p-4 sm:p-5 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col gap-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 flex items-center justify-center font-mono tabular-nums text-xs font-bold shrink-0 mt-0.5" 
                                          x-text="qIdx + 1"></span>
                                    <h3 class="text-sm font-semibold text-slate-900 leading-relaxed text-left" x-text="quiz.question"></h3>
                                </div>
                                <span x-show="quiz.is_answered" 
                                      :class="quiz.selected_option === quiz.correct_answer_index ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'"
                                      class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold shrink-0" 
                                      x-text="quiz.selected_option === quiz.correct_answer_index ? 'Benar ✓' : 'Kurang Tepat'">
                                </span>
                            </div>

                            <!-- Options: Full width tactile buttons min-h-14 -->
                            <div class="flex flex-col gap-2.5">
                                <template x-for="(opt, optIdx) in quiz.options" :key="optIdx">
                                    <button type="button" @click="selectQuizAnswer(qIdx, optIdx)"
                                            :disabled="quizAttemptPassed === true"
                                            :class="{
                                                'bg-emerald-50 border-emerald-400 text-emerald-900 font-semibold ring-1 ring-emerald-400': quiz.is_answered && optIdx === quiz.correct_answer_index,
                                                'bg-rose-50 border-rose-300 text-rose-800 line-through': quiz.is_answered && quiz.selected_option === optIdx && optIdx !== quiz.correct_answer_index,
                                                'bg-emerald-50 border-emerald-600 text-emerald-900 font-semibold ring-1 ring-emerald-600': !quiz.is_answered && quiz.selected_option === optIdx,
                                                'bg-white border-slate-200 hover:border-slate-300 text-slate-800 hover:bg-slate-50 shadow-2xs': !quiz.is_answered && quiz.selected_option !== optIdx,
                                                'bg-slate-100/60 border-slate-200 text-slate-400 opacity-60': quiz.is_answered && optIdx !== quiz.correct_answer_index && quiz.selected_option !== optIdx
                                            }"
                                            class="min-h-14 p-3.5 sm:p-4 rounded-xl border text-xs text-left transition-all flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 border border-slate-200 font-mono font-bold text-xs flex items-center justify-center shrink-0"
                                                  x-text="['A', 'B', 'C', 'D', 'E'][optIdx] || (optIdx + 1)"></span>
                                            <span class="font-medium text-left leading-relaxed" x-text="opt"></span>
                                        </div>
                                        <span x-show="quiz.is_answered && optIdx === quiz.correct_answer_index" class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
                                        <span x-show="quiz.is_answered && quiz.selected_option === optIdx && optIdx !== quiz.correct_answer_index" class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">cancel</span>
                                    </button>
                                </template>
                            </div>

                            <!-- Explanation Box -->
                            <div x-show="quiz.is_answered" 
                                 class="p-3.5 rounded-xl bg-indigo-50/60 border border-indigo-200/80 text-xs text-slate-700 flex items-start gap-2.5 text-left">
                                <span class="material-symbols-outlined text-[18px] text-indigo-700 shrink-0 mt-0.5">info</span>
                                <div>
                                    <span class="font-bold text-indigo-900">Penjelasan: </span>
                                    <span x-text="quiz.explanation"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end pt-2">
                        <button type="button" @click="submitAllQuizAnswers()" 
                                :disabled="isSubmittingQuiz || quizAttemptPassed === true"
                                class="min-h-11 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-40 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-xs">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            <span x-text="isSubmittingQuiz ? 'Memeriksa...' : (quizAttemptScore !== null ? 'Kirim Ulang Kuis' : 'Kirim Jawaban Kuis')"></span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== RIGHT COLUMN (30% - 4/12 cols — STICKY INTERACTIVE DOCK) ==================== -->
        <aside class="lg:col-span-4 lg:sticky lg:top-20 flex flex-col gap-4">
            <!-- Course Progress Summary Card -->
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col gap-3 text-left">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">SILABUS KURSUS</span>
                    <span class="text-xs font-bold font-mono tabular-nums text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full" 
                          x-text="progress + '% Selesai'">
                        {{ $progressPercentage }}% Selesai
                    </span>
                </div>

                <div class="flex flex-col gap-1.5">
                    <div class="flex justify-between items-baseline gap-2">
                        <h2 class="text-xs font-bold text-slate-900 truncate text-left">{{ $course->title }}</h2>
                        <span class="font-mono tabular-nums text-xs text-slate-500 shrink-0" 
                              x-text="completedCount + '/' + totalCount + ' Pelajaran'">
                            {{ $completedLessonsCount }}/{{ $totalLessonsCount }} Pelajaran
                        </span>
                    </div>
                    <!-- Linear Progress Bar -->
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-600 rounded-full transition-all duration-300" 
                             :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>

                <!-- Certificate Status Card -->
                <div x-show="certificateUrl" 
                     x-cloak 
                     @if(! $certificate) style="display: none;" @endif 
                     class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <div class="flex items-center justify-between text-[11px] text-amber-900 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200 font-medium">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-amber-600" style="font-variation-settings: 'FILL' 1;">verified</span>
                            <span>Syarat Kelulusan Terpenuhi</span>
                        </span>
                        <span class="font-bold uppercase tracking-wider text-[10px] bg-amber-200/80 text-amber-900 px-1.5 py-0.5 rounded">Lulus</span>
                    </div>
                    <a :href="certificateUrl" 
                       class="w-full min-h-11 py-2.5 px-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold flex items-center justify-center gap-2 shadow-xs transition-all">
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
                            <span class="material-symbols-outlined text-[16px] text-slate-500">lock</span>
                            <span>Syarat Sertifikat</span>
                        </span>
                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">Terkunci</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 flex flex-col gap-2 text-[11px]">
                        <div class="flex items-center justify-between" :class="completedCount >= totalCount ? 'text-emerald-700' : 'text-slate-600'">
                            <span class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px]" 
                                      :class="completedCount >= totalCount ? 'text-emerald-600' : 'text-slate-400'"
                                      x-text="completedCount >= totalCount ? 'check_circle' : 'radio_button_unchecked'">{{ $completedLessonsCount >= $totalLessonsCount ? 'check_circle' : 'radio_button_unchecked' }}</span>
                                <span>Tonton Semua Video</span>
                            </span>
                            <span class="font-mono tabular-nums text-[10px] font-semibold" 
                                  :class="completedCount >= totalCount ? 'text-emerald-700' : 'text-slate-500'"
                                  x-text="completedCount + '/' + totalCount">{{ $completedLessonsCount }}/{{ $totalLessonsCount }}</span>
                        </div>

                        @if($totalQuizzesCount > 0)
                            <div class="flex items-center justify-between" :class="hasPassedQuizzes ? 'text-emerald-700' : 'text-slate-600'">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px]" 
                                          :class="hasPassedQuizzes ? 'text-emerald-600' : 'text-slate-400'"
                                          x-text="hasPassedQuizzes ? 'check_circle' : 'radio_button_unchecked'">{{ $hasPassedAllQuizzes ? 'check_circle' : 'radio_button_unchecked' }}</span>
                                    <span>Kerjakan & Lulus Kuis</span>
                                </span>
                                <span class="font-mono tabular-nums text-[10px] font-semibold" 
                                      :class="hasPassedQuizzes ? 'text-emerald-700' : 'text-amber-800'"
                                      x-text="passedQuizzes + '/' + totalQuizzes">{{ $passedQuizzesCount }}/{{ $totalQuizzesCount }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column Tabs (Syllabus, Notes, Discussion) -->
            <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden flex flex-col">
                <!-- Tab Selector Bar (Min 44px Touch Target) -->
                <div class="flex border-b border-slate-200 bg-slate-50/75">
                    <button type="button" @click="activeRightTab = 'syllabus'" 
                            :class="activeRightTab === 'syllabus' ? 'border-emerald-600 text-emerald-800 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                            class="flex-1 min-h-11 flex items-center justify-center gap-1.5 py-3 border-b-2 text-xs transition-colors">
                        <span class="material-symbols-outlined text-[17px]">format_list_bulleted</span>
                        <span>Materi</span>
                    </button>
                    <button type="button" @click="activeRightTab = 'notes'" 
                            :class="activeRightTab === 'notes' ? 'border-emerald-600 text-emerald-800 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                            class="flex-1 min-h-11 flex items-center justify-center gap-1.5 py-3 border-b-2 text-xs transition-colors">
                        <span class="material-symbols-outlined text-[17px]">edit_note</span>
                        <span>Catatan</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-slate-200 text-slate-700 text-[10px] font-mono tabular-nums" x-text="notes.length"></span>
                    </button>
                    <button type="button" @click="activeRightTab = 'discussion'" 
                            :class="activeRightTab === 'discussion' ? 'border-emerald-600 text-emerald-800 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                            class="flex-1 min-h-11 flex items-center justify-center gap-1.5 py-3 border-b-2 text-xs transition-colors">
                        <span class="material-symbols-outlined text-[17px]">forum</span>
                        <span>Diskusi</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-slate-200 text-slate-700 text-[10px] font-mono tabular-nums" x-text="comments.length"></span>
                    </button>
                </div>

                <!-- Tab Pane 1: Syllabus -->
                <div x-show="activeRightTab === 'syllabus'" class="flex flex-col divide-y divide-slate-100 max-h-[640px] overflow-y-auto">
                    @foreach($modules as $moduleTitle => $lessonsInModule)
                        @php
                            $isCurrentModule = $lessonsInModule->contains('id', $currentLesson->id);
                            $moduleCompletedCount = $lessonsInModule->filter(fn($l) => $l->isCompletedBy($student))->count();
                            $moduleTotalCount = $lessonsInModule->count();
                        @endphp
                        <div class="flex flex-col" x-data="{ expanded: {{ $isCurrentModule ? 'true' : 'false' }} }">
                            <!-- Module Header Button -->
                            <button type="button" @click="expanded = !expanded" 
                                    class="w-full min-h-11 p-3.5 flex items-center justify-between text-left transition-colors {{ $isCurrentModule ? 'bg-emerald-50/40 border-l-2 border-emerald-600' : 'hover:bg-slate-50' }}">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-5 h-5 rounded-md {{ $isCurrentModule ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-mono tabular-nums text-[11px] font-bold shrink-0">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="truncate text-left">
                                        <h3 class="text-xs font-bold {{ $isCurrentModule ? 'text-emerald-900' : 'text-slate-800' }} truncate">
                                            {{ $moduleTitle }}
                                        </h3>
                                        <p class="text-[10px] text-slate-500 font-mono tabular-nums">{{ $moduleCompletedCount }}/{{ $moduleTotalCount }} Selesai</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-slate-400 shrink-0" 
                                      x-text="expanded ? 'expand_less' : 'expand_more'"></span>
                            </button>

                            <!-- Lessons in Module -->
                            <div x-show="expanded" class="flex flex-col divide-y divide-slate-100 bg-slate-50/40">
                                @foreach($lessonsInModule as $les)
                                    @php
                                        $isThisActive = $les->id === $currentLesson->id;
                                        $isThisDone = $les->isCompletedBy($student);
                                    @endphp

                                    @if($isThisActive)
                                        <div class="p-3 pl-4 border-l-2 border-emerald-600 bg-emerald-50 flex items-center justify-between min-h-11">
                                            <div class="flex items-center gap-2.5 min-w-0 text-left">
                                                <div class="w-4 h-4 rounded flex items-end justify-center gap-0.5 pb-0.5 shrink-0 bg-emerald-100 text-emerald-700">
                                                    <div class="w-0.5 bg-emerald-600 rounded-t h-3 animate-pulse"></div>
                                                    <div class="w-0.5 bg-emerald-600 rounded-t h-2 animate-pulse" style="animation-delay: 150ms;"></div>
                                                    <div class="w-0.5 bg-emerald-600 rounded-t h-3.5 animate-pulse" style="animation-delay: 300ms;"></div>
                                                </div>
                                                <div class="flex flex-col truncate">
                                                    <span class="text-xs font-bold text-emerald-900 truncate">
                                                        {{ $les->title }}
                                                    </span>
                                                    <span class="font-mono tabular-nums text-[10px] text-emerald-700">{{ $les->duration_formatted }} • Sedang Diputar</span>
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-bold shrink-0">
                                                Aktif
                                            </span>
                                        </div>
                                    @else
                                        <a href="{{ route('student.classroom.lesson', [$course->slug, $les->id]) }}" 
                                           class="p-3 pl-5 flex items-center justify-between hover:bg-slate-100/70 transition-colors group min-h-11">
                                            <div class="flex items-center gap-2.5 min-w-0 text-left">
                                                @if($isThisDone)
                                                    <span class="material-symbols-outlined text-[16px] text-emerald-600 shrink-0" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                                @else
                                                    <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-emerald-700 shrink-0">play_circle</span>
                                                @endif
                                                <div class="flex flex-col truncate">
                                                    <span class="text-xs {{ $isThisDone ? 'text-slate-500' : 'text-slate-800' }} group-hover:text-emerald-800 truncate font-medium">
                                                        {{ $les->title }}
                                                    </span>
                                                    <span class="font-mono tabular-nums text-[10px] text-slate-500">
                                                        {{ $les->duration_formatted }} {{ $isThisDone ? '• Selesai' : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-slate-600 shrink-0">
                                                chevron_right
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tab Pane 2: Notes -->
                <div x-show="activeRightTab === 'notes'" class="p-4 flex flex-col gap-4">
                    <!-- Frictionless Note Input Form -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col gap-3">
                        <textarea x-model="newNoteContent"
                                  @keydown.enter.ctrl="if(!newNoteTimestampStr) captureCurrentTimestamp(); saveNote()"
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50/60 text-slate-900 text-xs p-3 focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 focus:outline-none resize-none" 
                                  placeholder="Tulis hal penting yang ingin kamu ingat (Ctrl+Enter untuk simpan)..." rows="2"></textarea>
                        
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <button type="button" 
                                    @click="captureCurrentTimestamp()"
                                    class="min-h-11 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                                <span class="material-symbols-outlined text-[16px] text-emerald-700">timer</span>
                                <span x-text="newNoteTimestampStr ? 'Menit: ' + newNoteTimestampStr : '+ Ambil Menit Saat Ini'"></span>
                            </button>

                            <button type="button" 
                                    @click="if(!newNoteTimestampStr) captureCurrentTimestamp(); saveNote()" 
                                    :disabled="isSavingNote || !newNoteContent.trim()"
                                    class="min-h-11 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-40 text-white text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                                <span class="material-symbols-outlined text-[16px]">save</span>
                                <span x-text="isSavingNote ? 'Menyimpan...' : 'Simpan Catatan'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Notes List -->
                    <div class="flex flex-col gap-2.5 max-h-[460px] overflow-y-auto">
                        <template x-for="note in notes" :key="note.id">
                            <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs flex flex-col gap-2 text-left">
                                <div class="flex items-center justify-between gap-2">
                                    <button type="button" @click="seekVideo(note.timestamp_seconds)"
                                            title="Putar video dari menit ini"
                                            class="min-h-11 px-3 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-800 font-mono tabular-nums text-xs font-bold flex items-center gap-1.5 transition-colors">
                                        <span class="material-symbols-outlined text-[15px] text-emerald-600">play_circle</span>
                                        <span x-text="'▶ ' + note.formatted_timestamp + ' (Putar)'"></span>
                                    </button>
                                    <button type="button" @click="deleteNote(note.id)" title="Hapus Catatan"
                                            class="min-h-11 w-11 flex items-center justify-center text-slate-400 hover:text-rose-600 transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </div>
                                <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-line" x-text="note.content"></p>
                                <span class="text-[10px] text-slate-500 font-mono tabular-nums" x-text="note.created_at"></span>
                            </div>
                        </template>

                        <div x-show="notes.length === 0" class="py-8 text-center text-slate-500 bg-slate-50/80 rounded-2xl border border-dashed border-slate-200">
                            <span class="material-symbols-outlined text-[28px] text-slate-400 mb-1">note_stack</span>
                            <p class="text-xs font-medium text-slate-700">Belum ada catatan di materi ini.</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Simpan catatan dengan menit video untuk kamu buka kembali kapan saja.</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Pane 3: Discussion -->
                <div x-show="activeRightTab === 'discussion'" class="p-4 flex flex-col gap-4">
                    <!-- New Comment Box -->
                    <div class="flex flex-col gap-2">
                        <textarea x-model="newCommentContent"
                                  class="w-full rounded-xl border border-slate-300 bg-white text-slate-900 text-xs p-3 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 focus:outline-none resize-none shadow-2xs" 
                                  placeholder="Ajukan pertanyaan materi..." rows="2"></textarea>
                        <div class="flex items-center justify-between gap-2">
                            <input type="text" x-model="newCommentTimestamp"
                                   placeholder="Opsi waktu (misal: 14:12)" 
                                   class="px-2.5 py-1.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-900 font-mono tabular-nums w-36 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 focus:outline-none shadow-2xs"/>
                            <button type="button" @click="submitComment()" :disabled="isSubmittingComment || !newCommentContent.trim()"
                                    class="min-h-11 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                                <span class="material-symbols-outlined text-[16px]">send</span>
                                <span x-text="isSubmittingComment ? '...' : 'Kirim'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Discussion List -->
                    <div class="flex flex-col gap-3 max-h-[460px] overflow-y-auto">
                        <template x-for="comment in comments" :key="comment.id">
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col gap-2.5 text-left">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-7 h-7 rounded-full overflow-hidden shrink-0 ring-1 ring-slate-200 bg-slate-100">
                                        <img class="w-full h-full object-cover" :src="comment.user_avatar" :alt="comment.user_name"/>
                                    </div>
                                    <div class="flex flex-col gap-1 flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-xs font-bold text-slate-900 truncate" x-text="comment.user_name"></span>
                                            <span class="text-[10px] text-slate-500 font-mono tabular-nums" x-text="'• ' + comment.created_at"></span>
                                            <span x-show="comment.timestamp_str" class="px-1.5 py-0.2 rounded bg-indigo-50 text-indigo-700 text-[10px] font-mono tabular-nums border border-indigo-200" x-text="comment.timestamp_str"></span>
                                        </div>
                                        <p class="text-xs text-slate-700 leading-relaxed" x-text="comment.content"></p>

                                        <!-- Like & Reply Buttons -->
                                        <div class="flex items-center gap-3 pt-1 text-xs">
                                            <button type="button" @click="toggleLike(comment)" 
                                                    class="min-h-11 flex items-center gap-1 font-semibold transition-colors"
                                                    :class="comment.is_liked ? 'text-rose-600' : 'text-slate-500 hover:text-slate-900'">
                                                <span class="material-symbols-outlined text-[15px]" 
                                                      :style="comment.is_liked ? 'font-variation-settings: \'FILL\' 1;' : ''">favorite</span>
                                                <span class="font-mono tabular-nums" x-text="comment.likes_count"></span>
                                            </button>
                                            <button type="button" @click="comment.show_reply_box = !comment.show_reply_box" 
                                                    class="min-h-11 text-emerald-700 hover:text-emerald-800 font-semibold flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">reply</span>
                                                <span>Balas</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reply Form Box -->
                                <div x-show="comment.show_reply_box" class="ml-8 pt-2 flex flex-col gap-2">
                                    <textarea x-model="comment.reply_content"
                                              class="w-full rounded-xl border border-slate-300 bg-white text-slate-900 text-xs p-2.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 focus:outline-none resize-none shadow-2xs" 
                                              placeholder="Tulis balasan..." rows="2"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="comment.show_reply_box = false" 
                                                class="min-h-11 px-3 py-1.5 text-xs text-slate-600 hover:text-slate-900">Batal</button>
                                        <button type="button" @click="submitComment(comment.id, comment.reply_content)"
                                                class="min-h-11 px-3.5 py-1.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 shadow-xs">Kirim Balasan</button>
                                    </div>
                                </div>

                                <!-- Nested Replies -->
                                <template x-if="comment.replies && comment.replies.length > 0">
                                    <div class="ml-7 flex flex-col gap-2 pl-3 border-l border-slate-200 pt-1">
                                        <template x-for="reply in comment.replies" :key="reply.id">
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 flex items-start gap-2 shadow-2xs">
                                                <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 ring-1 ring-slate-200 bg-slate-100">
                                                    <img class="w-full h-full object-cover" :src="reply.user_avatar" :alt="reply.user_name"/>
                                                </div>
                                                <div class="flex flex-col gap-0.5 flex-1 min-w-0">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-xs font-bold text-slate-900 truncate" x-text="reply.user_name"></span>
                                                        <span class="text-[10px] text-slate-500 font-mono tabular-nums" x-text="'• ' + reply.created_at"></span>
                                                    </div>
                                                    <p class="text-xs text-slate-700" x-text="reply.content"></p>
                                                    <button type="button" @click="toggleLike(reply)" 
                                                            class="min-h-11 flex items-center gap-1 font-semibold transition-colors self-start"
                                                            :class="reply.is_liked ? 'text-rose-600' : 'text-slate-500 hover:text-slate-900'">
                                                        <span class="material-symbols-outlined text-[13px]" 
                                                              :style="reply.is_liked ? 'font-variation-settings: \'FILL\' 1;' : ''">favorite</span>
                                                        <span class="font-mono tabular-nums text-[11px]" x-text="reply.likes_count"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- ==================== GRADUATION CELEBRATION MODAL ==================== -->
    <div x-show="showGraduationModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="showGraduationModal = false"
             x-show="showGraduationModal"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-3"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-3"
             class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-amber-300 p-6 sm:p-8 text-left flex flex-col gap-4 text-slate-900">
            
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800">Kursus Selesai</span>
                    <h3 class="text-base font-bold text-slate-900">Sertifikat Kelulusan Resmi</h3>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Selamat! Anda telah menuntaskan seluruh video dan kuis checkpoint pada kursus <strong class="text-slate-900">{{ $course->title }}</strong>. Sertifikat resmi berverifikasi BKKBN Sulut telah diterbitkan.
            </p>

            <div class="w-full flex flex-col sm:flex-row items-center gap-2.5 pt-2">
                <a :href="certificateUrl"
                   class="w-full sm:flex-1 min-h-11 py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold flex items-center justify-center gap-2 shadow-xs transition-all">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span>Lihat & Unduh Sertifikat</span>
                </a>
                <button type="button" 
                        @click="showGraduationModal = false"
                        class="w-full sm:w-auto min-h-11 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</main>

<!-- ==================== YOUTUBE IFRAME API CONTROLLER ==================== -->
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
