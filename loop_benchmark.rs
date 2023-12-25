use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use std::thread;
use ctrlc::set_handler;
use rustc_version::{version};
use num_format::{Locale, ToFormattedString};

fn main() {
    let count_loops = Arc::new(AtomicUsize::new(0));

    set_handler({
        let count_loops_clone = Arc::clone(&count_loops);
        move || {
            println!("Rust {} incremented {} times.",
                     version().unwrap(),
                     count_loops_clone.load(Ordering::SeqCst).to_formatted_string(&Locale::en));
            std::process::exit(0);
        }
    }).expect("Error setting Ctrl-C handler");

    let mut handles = vec![];

    for _ in 0..12 {
        let count_loops_clone = Arc::clone(&count_loops);
        let handle = thread::spawn(move || {
            loop {
                count_loops_clone.fetch_add(1, Ordering::Relaxed);
            }
        });
        handles.push(handle);
    }

    for handle in handles {
        handle.join().unwrap();
    }
}